<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Loan;
use App\Models\LoanPayment;

class RepayLoanController extends Controller
{
    const FIRSTROW = 0;
    public function payLoan(Request $req){
        // Validating inputs
        $rules = array(
            "user_id"=>"required",
            "loan_id"=>"required",
            "amount"=>"required"
       );
       $validator = Validator::make($req->all(), $rules);
       if($validator->fails()){
           return $validator->errors(); 
       }
        
       // Extracting details fron $req
       $loanId = $req->loan_id;
       $moneySent = $req->amount;
       $userId = $req->user_id;
       try{
            DB::beginTransaction();
            // Finding the loan record
            $loan=Loan::find($loanId);
            if(!$loan or $loan->cust_id!==$userId){
                return $this->returnErrorRespose("Please check your loanId and UserId. Details do not match.");
            } 

            $loanTotalAmount = $loan->loan_amt;
            $loanTotalTerms = $loan->loan_terms;
            $loanStatus = $loan->loan_status;
            
            if($loanStatus === $this::PENDING){
                return $this->returnResponse(["message"=>"Loan status is PENDING. Please wait till loan is sanctioned."], 400, true);
            }
            if($loanStatus === $this::PAID){
                $loanPaidError = array(
                    'message' => "Loan has already been paid",
                    "Money Returned back" => "$".$moneySent
                );
                return $this->returnResponse($loanPaidError, 400, true);
            }

        
            $loanPmts = LoanPayment::where([['loan_id', '=', $req->loan_id], ['loan_pmt_status', 'PENDING'],])->orderBy('loan_pmt_sequence')->get();
            $loanPmtCount = count($loanPmts);

            $fullyPaidMsg = " ";
            $partiallyPaidMsg = " ";
            $loanClosedMsg = " ";

            for($idx=0; $idx<$loanPmtCount; $idx++){
                $currLoanPmt = $loanPmts[$idx];

                if($moneySent <= 0){
                    break;
                }
                if($idx==$this::FIRSTROW and $moneySent<$currLoanPmt->loan_pmt_amt){
                    $moneyNotEnoughError = array(
                        'message' => "Loan repayment failed for term ".$currLoanPmt->loan_pmt_sequence,
                        'Loan payment due date' => $currLoanPmt->loan_pmt_date,
                        'loan due' => "$". $currLoanPmt->loan_pmt_amt,
                        'loan payment status' => $currLoanPmt->loan_pmt_status
                    );
                    return $this->returnResponse([$moneyNotEnoughError], 400, true);
                }
                $moneySent = number_format(($moneySent -$currLoanPmt->loan_pmt_amt), 2, '.', '');
                if($moneySent>=0){
                    $currLoanPmt->loan_pmt_amt = 0;
                    $currLoanPmt->loan_pmt_status = $this::PAID;
                    $fullyPaidMsg = $fullyPaidMsg."Term ".$currLoanPmt->loan_pmt_sequence.", ";
                }
                else {
                    $currLoanPmt->loan_pmt_amt=abs(number_format(($moneySent),2, '.', ''));
                    $partiallyPaidMsg = $partiallyPaidMsg."Term ".$currLoanPmt->loan_pmt_sequence;
                }
                $currLoanPmt->save();
            }
            if($idx>=$loanPmtCount and $moneySent>=0){
                $loan->loan_status = $this::PAID;
                $loan->save();
                $loanClosedMsg = $loanClosedMsg."Loan fully paid. Money Returned: $".$moneySent;
            }
            DB::commit();
            return $this->returnResponse(["Fully Paid Term(s)"=>$fullyPaidMsg, "Partially Paid Term"=>$partiallyPaidMsg, "Loan closed"=>$loanClosedMsg], 200, false);
        }
        catch(\Exception $e) {
            DB::rollBack();
            return $this->returnResponse(["SQL Exception"=>$e->getMessage()], 500, true);
            
        } 
    }
}
