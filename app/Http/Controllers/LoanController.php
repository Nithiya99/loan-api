<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Models\Loan;
use App\Models\LoanPayment;
use App\Models\User;

class LoanController extends Controller
{
    const ADMIN = "Admin";
    const CLIENT = "Client";
    
    public function getLoans(){
        return Loan::all();
    }

    public function getLoansByCustId($custId){
        try {
            $data = Loan::where('cust_id', $custId)->get();

            if(count($data)===0){
                return $this->returnResponse(["Loan Found"=>"None - Please check your ID"], 400, true);
            } else{
                return $this->returnResponse(["Loan Found"=>$data], 200, false);
            }
        }
        catch(\Exception $e) {
            return $this->returnResponse(["SQL Exception"=>$e->getMessage()], 500, true);
        } 
        // return Loan::where('cust_id', $custId)->get();
    }

    public function createLoan(Request $req){

        // Validating inputs
        $rules = array(
             "loan_amt"=>"required|max:8|gt:0",
             "loan_terms"=>"required|gt:0",
             "cust_id"=>"required"
        );
        $validator = Validator::make($req->all(), $rules);
        if($validator->fails()){
            return $this->returnResponse(["Validation Errors"=>$validator->errors()], 400, true);
       }

        try{
            $user = User::find($req->cust_id);
            if(!$user)
               return $this->returnResponse(["message"=>"No User Found"], 400, true);
            if($user->user_type!==$this::CLIENT)
                return $this->returnResponse(["message"=>"User is not a Client"], 400, true);

            
            DB::beginTransaction();
            $loan = new Loan;
            $loan->loan_amt=$req->loan_amt;
            $loan->loan_terms=$req->loan_terms;
            $loan->loan_status=$this::PENDING;
            $loan->cust_id=$req->cust_id;
            $result=$loan->save();

            $this->createLoanPmts($loan);
           DB::commit();

           return $this->returnResponse(["message"=>$loan], 200, false);

        }
        catch(\Exception $e) {
            DB::rollBack();
            return $this->returnResponse(["SQL Exception"=>$e->getMessage()], 500, true);
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
            $loanPmt->save();
        }
    }

    public function updateLoan(Request $req){
        // Validating inputs
        $rules = array(
            "user_id"=>"required",
            "loan_id"=>"required",
       );
       $validator = Validator::make($req->all(), $rules);

       if($validator->fails()){
            return $this->returnResponse(["Validation Errors"=>$validator->errors()], 400, true);
       }

       try{
           $loan=Loan::find($req->loan_id);
           if(!$loan)
                return $this->returnResponse(["message"=>"Loan does not exist"], 400, true);
            if($loan->loan_status === $this::APPROVED or $loan->loan_status === $this::PAID)
                return $this->returnResponse(["message"=>"Loan status is already PAID or APPROVED"], 400, true);

           $user = User::find($req->user_id);
           if(!$user)
               return $this->returnResponse(["message"=>"No User Found"], 400, true);
            if($user->user_type!==$this::ADMIN)
                return $this->returnResponse(["message"=>"User is not Admin"], 400, true);

           DB::beginTransaction();
           $loan->loan_status=$this::APPROVED;
           $loan->loan_approve_id = $req->user_id;
           $loan->loan_approve_date = now();
           $result=$loan->save();
           DB::commit();
           return $this->returnResponse(["message"=>$loan], 200, false);
       }
       catch(\Exception $e) {
            DB::rollBack();
            return $this->returnResponse(["SQL Exception"=>$e->getMessage()], 500, true);
        } 
        
    }
}
