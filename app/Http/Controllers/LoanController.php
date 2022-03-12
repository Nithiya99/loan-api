<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;

class LoanController extends Controller
{
    
    public function getLoans(){
        return Loan::all();
    }

    public function getLoansByCustId($custId){
        $data = Loan::where('cust_id', $custId)->get();
        if(count($data)===0){
            return $this->returnErrorRespose("No loans found for your ID. Please check your ID.");
        } else{
            return $this->returnSuccessResponse("Loans you have applied for", $data);
        }
        // return Loan::where('cust_id', $custId)->get();
    }

    public function createLoan(Request $req){
        $user = User::find($req->cust_id);
        if($user){
            if($user->user_type==="Client"){
                $loan = new Loan;
                // $loan->loan_id=$req->loan_id;
                $loan->loan_amt=$req->loan_amt;
                $loan->loan_terms=$req->loan_terms;
                $loan->loan_status="PENDING";
                $loan->cust_id=$req->cust_id;
                $result=$loan->save();
                if($result){
                    return $this->createLoanPmts($loan);
                    // return ["Result"=>"Loan created successfully"];
                    
                    // return $result;
                } else {
                    return $this->returnErrorRespose("Loan creation failed.");
                }
            } else {
                return $this->returnUnauthorizedResponse();
            }
        }
        else {
            return $this->returnUnauthorizedResponse();
        }
    }

    public function createLoanPmts($loan){
        
        for($i=1; $i<=$loan->loan_terms; $i++){
            $loanPmt = new LoanPayment;
            // Storing the loan_id as foreign key
            $loanPmt->loan_id=$loan->loan_id;

            // Generating the sequence repayment term number
            $loanPmt->loan_pmt_sequence=($i);

            // Calculating how much to pay for the i-th term
            if($i === $loan->loan_terms){
                $pmt_per_term = number_format(($loan->loan_amt)/ ($loan->loan_terms), 2, '.', '');
                $loanPmt->loan_pmt_amt=$loan->loan_amt-($pmt_per_term*($loan->loan_terms-1));
            } else {
                $loanPmt->loan_pmt_amt=number_format(($loan->loan_amt)/ ($loan->loan_terms), 2, '.', '');
            }

            $loanPmt->loan_pmt_status=$loan->loan_status;
            $loanPmt->loan_pmt_date=$loan->created_at->addDays($i*7);
            $result=$loanPmt->save();
        }
        if($result){
            // return ["Result"=>"Loan fully created successfully"];
            return $this->returnSuccessResponse("Loan applied successfully!", $loan);
            
            // return $result;
        } else {
            return  $this->returnErrorRespose("Loan creation failed");
        }
        // return["Result"=>$loan];
    }

    public function updateLoan(Request $req){
        $user = User::find($req->user_id);
        if($user){
            if($user->user_type==="Admin"){
                $loan=Loan::find($req->loan_id);
                if($loan){
                    if($loan->loan_status === "PENDING"){
                        $loan->loan_status="APPROVED";
                        $loan->loan_approve_id = $req->user_id;
                        $loan->loan_approve_date = now();
                        $result=$loan->save();
                        if($result){
                            return $this->returnSuccessResponse("Loan status changed to APPROVED!", $loan);
                            // return $result;
                        } else {
                            return ["Result"=>"Loan updation Failed!"];
                        }
                    } elseif($loan->loan_status === "PAID") {
                        return  $this->returnErrorRespose("Loan is already PAID.");
                    } else {
                        return  $this->returnErrorRespose("Loan is already APPROVED.");
                    }
                }
                else {
                    return  $this->returnErrorRespose("There is no loan applied with the given ID!");
                }
            } else {
                return $this->returnUnauthorizedResponse();
            }
        } else {
            return $this->returnUnauthorizedResponse();
        }
        
    }
}
