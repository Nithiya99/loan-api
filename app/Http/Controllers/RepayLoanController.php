<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Models\Loan;
use App\Models\LoanPayment;

class RepayLoanController extends Controller
{
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

        // These arrays will be used to store and send custom responses.
        $errorOutput = array(
            'error' => true,
            'code' => 500
        ); 
        $successOutput = array(
            'error'=>false,
            'code'=>200
        );

        // Extracting details fron $req
        $loanId = $req->loan_id;
        $moneySent = $req->amount;
        $userId = $req->user_id;

        // Finding the loan record
        $loan=Loan::find($loanId);
        if(!$loan or $loan->cust_id!==$userId){
            return $this->returnErrorRespose("Please check your loanId and UserId. Details do not match.");
        } else {
            $loanTotalAmount = $loan->loan_amt;
            $loanTotalTerms = $loan->loan_terms;
            $loanStatus = $loan->loan_status;

            // Check if loan is approved
            if($loanStatus === "PENDING"){
                return $this->returnErrorRespose("Loan status is PENDING. Please wait till loan is sanctioned.");
            }
            elseif($loanStatus === "PAID"){
                $loanPendingError = array(
                    'message' => "Loan has already been paid",
                    "Money Returned back" => "$".$moneySent
                );
                $errorOutput = array_merge($loanPendingError, $errorOutput);
                return response()->json([$errorOutput], 500);
                
            }

            // Now, loanStatus is APPROVED.
            
            // Retreving the loan payments with status PENDING and ordering them by the payment sequence number.
            $loanPmts=DB::table('loan_payments')->where([['loan_id', '=', $req->loan_id], ['loan_pmt_status', 'PENDING'],])->orderBy('loan_pmt_sequence')->get();

            // If money sent is less than loan_pmt for the upcoming period => Return ERROR
            foreach($loanPmts as $i=>$i_value){
                if($moneySent < $i_value->loan_pmt_amt){
                    if($i === 0) {
                        $moneyNotEnoughError = array(
                            'message' => "Loan repayment failed for term ".$i_value->loan_pmt_sequence.". Money sent is less than loan payment due which is $". $i_value->loan_pmt_amt,
                            'Loan payment due date' => $i_value->loan_pmt_date,
                            'loan due' => "$". $i_value->loan_pmt_amt,
                            'loan payment status' => $i_value->loan_pmt_status
                        );
                        $errorOutput = array_merge($moneyNotEnoughError,$errorOutput);
                        return response()->json([$errorOutput], 500);
                    }
                    else {
                        DB::beginTransaction();
                        try {
                            $loanPmt = LoanPayment::find($i_value->loan_pmt_id);
                            $loanPmt->loan_pmt_amt = number_format(($i_value->loan_pmt_amt-$moneySent ), 2, '.', '');
                            $loanPmt->save();
                            DB::commit();
                            $nextLoanTermDetails = array(
                                'Loan Term'=>$i_value->loan_pmt_sequence,
                                'Loan Due on' => $i_value->loan_pmt_date,
                                'Loan Due Amount' => $loanPmt->loan_pmt_amt
                            );
                            $successOutput = array_merge($successOutput,$nextLoanTermDetails);
                            return response()->json([$successOutput], 201);
                        }
                        catch(\Exception $e) {
                            DB::rollBack();
                            return $this->returnErrorRespose($e->getMessage());
                        } 
                    }
                }
                elseif($moneySent === $i_value->loan_pmt_amt){
                   DB::beginTransaction();
                   
                   try{
                       $loanPmt = LoanPayment::find($i_value->loan_pmt_id);
                       $loanPmt->loan_pmt_amt = $i_value->loan_pmt_amt - $moneySent;
                       $loanPmt->loan_pmt_status = "PAID";
                        // Checking if all the loan dues are paid.
                       if($i_value->loan_pmt_sequence === $loanTotalTerms){
                           $loan = Loan::find($i_value->loan_id);
                           $loan->loan_status = "PAID";
                           $loan->save();
                           $fullLoanPaid = array(
                               'loan completed'=> "All loan dues are PAID.",
                               'Loan Completed Details' => $loan
                           );
                           $successOutput = array_merge($fullLoanPaid,$successOutput);
                       }
                       $loanPmt->save();

                       DB::commit();
                       $loanPaid = array (
                           'message'=>"Loan due for term ".$i_value->loan_pmt_sequence." of amount $". $i_value->loan_pmt_amt." has been paid successfully.",
                           'Loan Term Payment Details' => $loanPmt
                       );
                       $successOutput = array_merge($loanPaid,$successOutput);
                       return response()->json([$successOutput], 201);
                   } catch(\Exception $e) {
                       DB::rollBack();
                       return $this->returnErrorRespose($e->getMessage());
                   }
               }
               elseif($moneySent > $i_value->loan_pmt_amt){
                   DB::beginTransaction();
                   try {
                       $loanPmt = LoanPayment::find($i_value->loan_pmt_id);
                       $loanPmt->loan_pmt_amt = 0.00;
                       $loanPmt->loan_pmt_status = "PAID";
                       
                       // Updating the remaining money the user sent
                       $moneySent = number_format(($moneySent - $i_value->loan_pmt_amt), 2, '.', '');
                       if($i_value->loan_pmt_sequence === $loanTotalTerms){
                           $loan = Loan::find($i_value->loan_id);
                           $loan->loan_status = "PAID";
                           $loan->save();
                           $fullLoanPaid = array(
                               'loan completed'=> "All loan dues are PAID.",
                               'Money Returned'=>$moneySent,
                               'Loan Completed Details' => $loan,
                               'Loan Completed Details' => $loan
                           );
                           
                           $successOutput = array_merge($fullLoanPaid,$successOutput);
                       }
                       else {
                        $loanPmt->save();
                        DB::commit();
                        $loanPaid = array (
                            'message'=>"Loan due for term ".$i_value->loan_pmt_sequence." of amount $". $i_value->loan_pmt_amt." has been paid successfully.",
                            'Loan Term Payment Details' => $loanPmt
                        );
                        $successOutput = array_merge($loanPaid,$successOutput);
                        continue;
                       }
                       $loanPmt->save();
                       DB::commit();
                       
                       $loanPaid = array (
                           'message'=>"Loan due for term ".$i_value->loan_pmt_sequence." of amount $". $i_value->loan_pmt_amt." has been paid successfully.",
                           'Loan Term Payment Details'=>$loanPmt,
                       );
                       $successOutput = array_merge($loanPaid,$successOutput);
                       return response()->json([$successOutput], 201);
                       
                   } catch(\Exception $e){
                       DB::rollBack();
                        return $this->returnErrorRespose($e->getMessage());
                   }
               }
            }
        }
        
    }
}
