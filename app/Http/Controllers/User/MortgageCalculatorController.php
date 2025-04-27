<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MortgageCalculatorController extends Controller
{
    /**
     * Affiche la page du calculateur hypothécaire
     */
    public function index()
    {
        return view('mortgage.calculator');
    }

    /**
     * Version publique du calculateur hypothécaire
     */
    public function publicIndex()
    {
        return view('mortgage.calculator');
    }

    /**
     * Traite les calculs hypothécaires
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'loan_amount' => 'required|numeric|min:100000',
            'interest_rate' => 'required|numeric|min:0.1|max:30',
            'loan_term' => 'required|integer|min:1|max:30',
        ]);

        $loanAmount = $request->input('loan_amount');
        $interestRate = $request->input('interest_rate');
        $loanTerm = $request->input('loan_term');

        // Calcul des mensualités
        $monthlyRate = $interestRate / 100 / 12;
        $numberOfPayments = $loanTerm * 12;
        $x = pow(1 + $monthlyRate, $numberOfPayments);
        $monthlyPayment = ($loanAmount * $monthlyRate * $x) / ($x - 1);

        // Calcul du coût total et des intérêts
        $totalCost = $monthlyPayment * $numberOfPayments;
        $totalInterest = $totalCost - $loanAmount;

        // Calcul du tableau d'amortissement
        $amortizationTable = $this->calculateAmortizationTable($loanAmount, $interestRate, $loanTerm);

        return response()->json([
            'monthly_payment' => $monthlyPayment,
            'total_cost' => $totalCost,
            'total_interest' => $totalInterest,
            'amortization_table' => $amortizationTable,
        ]);
    }

    /**
     * Calcule le tableau d'amortissement
     */
    private function calculateAmortizationTable($loanAmount, $interestRate, $loanTerm)
    {
        $table = [];
        $monthlyRate = $interestRate / 100 / 12;
        $numberOfPayments = $loanTerm * 12;
        $x = pow(1 + $monthlyRate, $numberOfPayments);
        $monthlyPayment = ($loanAmount * $monthlyRate * $x) / ($x - 1);

        $remainingCapital = $loanAmount;
        $totalInterestPaid = 0;
        $totalCapitalPaid = 0;

        for ($year = 1; $year <= $loanTerm; $year++) {
            $yearlyInterestPaid = 0;
            $yearlyCapitalPaid = 0;

            for ($month = 1; $month <= 12; $month++) {
                if ($remainingCapital <= 0) break;

                $interestForMonth = $remainingCapital * $monthlyRate;
                $capitalForMonth = $monthlyPayment - $interestForMonth;

                if ($capitalForMonth > $remainingCapital) {
                    $capitalForMonth = $remainingCapital;
                    $monthlyPayment = $capitalForMonth + $interestForMonth;
                }

                $remainingCapital -= $capitalForMonth;
                $yearlyInterestPaid += $interestForMonth;
                $yearlyCapitalPaid += $capitalForMonth;
            }

            $totalInterestPaid += $yearlyInterestPaid;
            $totalCapitalPaid += $yearlyCapitalPaid;

            $table[] = [
                'year' => $year,
                'capital_paid' => $yearlyCapitalPaid,
                'interest_paid' => $yearlyInterestPaid,
                'total_capital_paid' => $totalCapitalPaid,
                'total_interest_paid' => $totalInterestPaid,
                'remaining_capital' => $remainingCapital,
            ];
        }

        return $table;
    }

    /**
     * Calcule la capacité d'emprunt
     */
    public function calculateBorrowingCapacity(Request $request)
    {
        $request->validate([
            'monthly_income' => 'required|numeric|min:50000',
            'monthly_debt' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|min:0.1|max:30',
            'loan_term' => 'required|integer|min:1|max:30',
        ]);

        $monthlyIncome = $request->input('monthly_income');
        $monthlyDebt = $request->input('monthly_debt');
        $downPayment = $request->input('down_payment');
        $interestRate = $request->input('interest_rate');
        $loanTerm = $request->input('loan_term');

        // On considère généralement qu'on ne doit pas dépasser 33% des revenus pour le remboursement
        $maxDebtPayment = $monthlyIncome * 0.33 - $monthlyDebt;
        $maxMonthlyPayment = max(0, $maxDebtPayment);

        // Calcul de la capacité d'emprunt
        $borrowingCapacity = 0;
        if ($maxMonthlyPayment > 0) {
            $monthlyRate = $interestRate / 100 / 12;
            $numberOfPayments = $loanTerm * 12;
            $x = pow(1 + $monthlyRate, $numberOfPayments);
            $borrowingCapacity = ($maxMonthlyPayment * ($x - 1)) / ($monthlyRate * $x);
        }

        $totalBorrowingCapacity = $borrowingCapacity + $downPayment;

        return response()->json([
            'max_monthly_payment' => $maxMonthlyPayment,
            'borrowing_capacity' => $borrowingCapacity,
            'total_borrowing_capacity' => $totalBorrowingCapacity,
        ]);
    }

    /**
     * Version publique du calcul
     */
    public function publicCalculate(Request $request)
    {
        return $this->calculate($request);
    }
}
