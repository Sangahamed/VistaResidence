@extends('layouts.app')

@section('title', 'Calculateur de prêt immobilier')

@section('styles')
<style>
    .slider-container {
        margin-bottom: 1.5rem;
    }
    .result-card {
        background-color: #f8f9fa;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-card {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    .tab-content {
        padding-top: 1.5rem;
    }
    .amortization-table {
        width: 100%;
        border-collapse: collapse;
    }
    .amortization-table th, .amortization-table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
    }
    .amortization-table th {
        background-color: #f8f9fa;
    }
    .tooltip-icon {
        cursor: help;
        color: #6c757d;
        margin-left: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-2">Calculateur de prêt immobilier</h1>
            <p class="text-muted">Estimez vos mensualités ou votre capacité d'emprunt pour votre projet immobilier.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <ul class="nav nav-tabs" id="calculatorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab" aria-controls="monthly" aria-selected="true">Calculateur de mensualités</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="capacity-tab" data-bs-toggle="tab" data-bs-target="#capacity" type="button" role="tab" aria-controls="capacity" aria-selected="false">Capacité d'emprunt</button>
                </li>
            </ul>

            <div class="tab-content" id="calculatorTabsContent">
                <!-- Onglet Calculateur de mensualités -->
                <div class="tab-pane fade show active" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="card-title h5 mb-0">Calculez vos mensualités</h2>
                        </div>
                        <div class="card-body">
                            <form id="monthlyCalculatorForm">
                                <div class="mb-4">
                                    <label for="loan-amount" class="form-label d-flex justify-content-between">
                                        <span>Montant du prêt</span>
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" id="loan-amount-input" class="form-control text-end" value="10000000">
                                            <span class="input-group-text">XOF</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="loan-amount" min="1000000" max="100000000" step="500000" value="10000000">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">1 000 000 XOF</small>
                                        <small class="text-muted">100 000 000 XOF</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="interest-rate" class="form-label d-flex justify-content-between">
                                        <span>Taux d'intérêt</span>
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <input type="number" id="interest-rate-input" class="form-control text-end" value="5.5" step="0.1">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="interest-rate" min="0.1" max="15" step="0.1" value="5.5">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">0.1 %</small>
                                        <small class="text-muted">15 %</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="loan-term" class="form-label d-flex justify-content-between">
                                        <span>Durée du prêt</span>
                                        <div class="input-group input-group-sm" style="width: 100px;">
                                            <input type="number" id="loan-term-input" class="form-control text-end" value="15">
                                            <span class="input-group-text">ans</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="loan-term" min="1" max="30" step="1" value="15">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">1 an</small>
                                        <small class="text-muted">30 ans</small>
                                    </div>
                                </div>
                            </form>

                            <div class="result-card">
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <p class="text-muted mb-1">Mensualité</p>
                                        <h3 id="monthly-payment" class="mb-0">0 XOF</h3>
                                    </div>
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <p class="text-muted mb-1">Coût total des intérêts</p>
                                        <h3 id="total-interest" class="mb-0">0 XOF</h3>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">Coût total du crédit</p>
                                        <h3 id="total-cost" class="mb-0">0 XOF</h3>
                                    </div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Information</h5>
                                        <p class="mb-0">Ce calculateur fournit une estimation. Les taux et conditions réels peuvent varier selon votre profil, votre banque et les conditions du marché.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h5 mb-0">Tableau d'amortissement</h2>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped amortization-table" id="amortization-table">
                                    <thead>
                                        <tr>
                                            <th>Année</th>
                                            <th>Capital remboursé</th>
                                            <th>Intérêts payés</th>
                                            <th>Capital restant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Le tableau sera rempli par JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Onglet Capacité d'emprunt -->
                <div class="tab-pane fade" id="capacity" role="tabpanel" aria-labelledby="capacity-tab">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h2 class="card-title h5 mb-0">Calculez votre capacité d'emprunt</h2>
                        </div>
                        <div class="card-body">
                            <form id="capacityCalculatorForm">
                                <div class="mb-4">
                                    <label for="monthly-income" class="form-label d-flex justify-content-between">
                                        <span>
                                            Revenus mensuels nets
                                            <i class="fas fa-question-circle tooltip-icon" data-bs-toggle="tooltip" title="Indiquez le total des revenus nets mensuels de votre foyer (salaires, revenus fonciers, etc.)"></i>
                                        </span>
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" id="monthly-income-input" class="form-control text-end" value="500000">
                                            <span class="input-group-text">XOF</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="monthly-income" min="100000" max="5000000" step="50000" value="500000">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">100 000 XOF</small>
                                        <small class="text-muted">5 000 000 XOF</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="monthly-debt" class="form-label d-flex justify-content-between">
                                        <span>
                                            Charges mensuelles
                                            <i class="fas fa-question-circle tooltip-icon" data-bs-toggle="tooltip" title="Indiquez le total de vos charges mensuelles (autres crédits en cours, pensions alimentaires, etc.)"></i>
                                        </span>
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" id="monthly-debt-input" class="form-control text-end" value="100000">
                                            <span class="input-group-text">XOF</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="monthly-debt" min="0" max="1000000" step="10000" value="100000">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">0 XOF</small>
                                        <small class="text-muted">1 000 000 XOF</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="down-payment" class="form-label d-flex justify-content-between">
                                        <span>Apport personnel</span>
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="number" id="down-payment-input" class="form-control text-end" value="2000000">
                                            <span class="input-group-text">XOF</span>
                                        </div>
                                    </label>
                                    <input type="range" class="form-range" id="down-payment" min="0" max="50000000" step="1000000" value="2000000">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">0 XOF</small>
                                        <small class="text-muted">50 000 000 XOF</small>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="borrowing-rate" class="form-label">Taux d'intérêt</label>
                                        <select class="form-select" id="borrowing-rate">
                                            <option value="4.5">4.5 %</option>
                                            <option value="5.0">5.0 %</option>
                                            <option value="5.5" selected>5.5 %</option>
                                            <option value="6.0">6.0 %</option>
                                            <option value="6.5">6.5 %</option>
                                            <option value="7.0">7.0 %</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="borrowing-term" class="form-label">Durée du prêt</label>
                                        <select class="form-select" id="borrowing-term">
                                            <option value="10">10 ans</option>
                                            <option value="15" selected>15 ans</option>
                                            <option value="20">20 ans</option>
                                            <option value="25">25 ans</option>
                                            <option value="30">30 ans</option>
                                        </select>
                                    </div>
                                </div>
                            </form>

                            <div class="result-card">
                                <div class="row text-center">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <p class="text-muted mb-1">Capacité d'emprunt maximale</p>
                                        <h3 id="borrowing-capacity" class="mb-0">0 XOF</h3>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="text-muted mb-1">Mensualité maximale</p>
                                        <h3 id="max-monthly-payment" class="mb-0">0 XOF</h3>
                                        <p class="text-muted small">(33% de vos revenus nets - vos charges actuelles)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Information</h5>
                                        <p class="mb-0">Cette estimation est basée sur un taux d'endettement maximal de 33% de vos revenus nets. Les banques peuvent appliquer des critères différents selon votre profil et leur politique de risque.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h2 class="card-title h5 mb-0">Besoin d'aide pour votre projet ?</h2>
                            <i class="fas fa-calculator fa-lg text-muted"></i>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="border rounded p-3 h-100">
                                        <h5 class="mb-2">Prendre rendez-vous</h5>
                                        <p class="text-muted mb-3">Rencontrez un conseiller pour étudier votre projet en détail et obtenir une simulation personnalisée.</p>
                                        <button class="btn btn-primary w-100">Prendre rendez-vous</button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <h5 class="mb-2">Être rappelé</h5>
                                        <p class="text-muted mb-3">Laissez-nous vos coordonnées et un conseiller vous contactera dans les plus brefs délais.</p>
                                        <button class="btn btn-secondary w-100">Demander à être rappelé</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Formater les nombres en XOF
        function formatXOF(value) {
            return new Intl.NumberFormat('fr-FR', { 
                style: 'currency', 
                currency: 'XOF',
                maximumFractionDigits: 0
            }).format(value);
        }

        // Calculateur de mensualités
        const loanAmountSlider = document.getElementById('loan-amount');
        const loanAmountInput = document.getElementById('loan-amount-input');
        const interestRateSlider = document.getElementById('interest-rate');
        const interestRateInput = document.getElementById('interest-rate-input');
        const loanTermSlider = document.getElementById('loan-term');
        const loanTermInput = document.getElementById('loan-term-input');
        const monthlyPaymentElement = document.getElementById('monthly-payment');
        const totalInterestElement = document.getElementById('total-interest');
        const totalCostElement = document.getElementById('total-cost');
        const amortizationTableBody = document.querySelector('#amortization-table tbody');

        // Synchroniser les sliders et les inputs
        loanAmountSlider.addEventListener('input', function() {
            loanAmountInput.value = this.value;
            calculateMonthlyPayment();
        });

        loanAmountInput.addEventListener('input', function() {
            loanAmountSlider.value = this.value;
            calculateMonthlyPayment();
        });

        interestRateSlider.addEventListener('input', function() {
            interestRateInput.value = this.value;
            calculateMonthlyPayment();
        });

        interestRateInput.addEventListener('input', function() {
            interestRateSlider.value = this.value;
            calculateMonthlyPayment();
        });

        loanTermSlider.addEventListener('input', function() {
            loanTermInput.value = this.value;
            calculateMonthlyPayment();
        });

        loanTermInput.addEventListener('input', function() {
            loanTermSlider.value = this.value;
            calculateMonthlyPayment();
        });

        // Calculer les mensualités
        function calculateMonthlyPayment() {
            const loanAmount = parseFloat(loanAmountSlider.value);
            const interestRate = parseFloat(interestRateSlider.value);
            const loanTerm = parseInt(loanTermSlider.value);

            if (loanAmount > 0 && interestRate > 0 && loanTerm > 0) {
                const monthlyRate = interestRate / 100 / 12;
                const numberOfPayments = loanTerm * 12;
                const x = Math.pow(1 + monthlyRate, numberOfPayments);
                const monthly = (loanAmount * monthlyRate * x) / (x - 1);

                const totalCost = monthly * numberOfPayments;
                const totalInterest = totalCost - loanAmount;

                monthlyPaymentElement.textContent = formatXOF(monthly);
                totalInterestElement.textContent = formatXOF(totalInterest);
                totalCostElement.textContent = formatXOF(totalCost);

                updateAmortizationTable(loanAmount, interestRate, loanTerm);
            }
        }

        // Mettre à jour le tableau d'amortissement
        function updateAmortizationTable(loanAmount, interestRate, loanTerm) {
            amortizationTableBody.innerHTML = '';

            const monthlyRate = interestRate / 100 / 12;
            const numberOfPayments = loanTerm * 12;
            const x = Math.pow(1 + monthlyRate, numberOfPayments);
            const monthlyPayment = (loanAmount * monthlyRate * x) / (x - 1);

            let remainingCapital = loanAmount;
            let totalCapitalPaid = 0;
            let totalInterestPaid = 0;

            for (let year = 1; year <= Math.min(loanTerm, 10); year++) {
                let yearlyCapitalPaid = 0;
                let yearlyInterestPaid = 0;

                for (let month = 1; month <= 12; month++) {
                    if (remainingCapital <= 0) break;

                    const interestForMonth = remainingCapital * monthlyRate;
                    let capitalForMonth = monthlyPayment - interestForMonth;

                    if (capitalForMonth > remainingCapital) {
                        capitalForMonth = remainingCapital;
                    }

                    remainingCapital -= capitalForMonth;
                    yearlyCapitalPaid += capitalForMonth;
                    yearlyInterestPaid += interestForMonth;
                }

                totalCapitalPaid += yearlyCapitalPaid;
                totalInterestPaid += yearlyInterestPaid;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${year}</td>
                    <td>${formatXOF(yearlyCapitalPaid)}</td>
                    <td>${formatXOF(yearlyInterestPaid)}</td>
                    <td>${formatXOF(remainingCapital)}</td>
                `;
                amortizationTableBody.appendChild(row);
            }

            if (loanTerm > 10) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="4" class="text-center text-muted">
                        <i class="fas fa-chevron-down"></i>
                        <span class="ms-2">Tableau limité aux 10 premières années</span>
                    </td>
                `;
                amortizationTableBody.appendChild(row);
            }
        }

        // Calculateur de capacité d'emprunt
        const monthlyIncomeSlider = document.getElementById('monthly-income');
        const monthlyIncomeInput = document.getElementById('monthly-income-input');
        const monthlyDebtSlider = document.getElementById('monthly-debt');
        const monthlyDebtInput = document.getElementById('monthly-debt-input');
        const downPaymentSlider = document.getElementById('down-payment');
        const downPaymentInput = document.getElementById('down-payment-input');
        const borrowingRateSelect = document.getElementById('borrowing-rate');
        const borrowingTermSelect = document.getElementById('borrowing-term');
        const borrowingCapacityElement = document.getElementById('borrowing-capacity');
        const maxMonthlyPaymentElement = document.getElementById('max-monthly-payment');

        // Synchroniser les sliders et les inputs pour la capacité d'emprunt
        monthlyIncomeSlider.addEventListener('input', function() {
            monthlyIncomeInput.value = this.value;
            calculateBorrowingCapacity();
        });

        monthlyIncomeInput.addEventListener('input', function() {
            monthlyIncomeSlider.value = this.value;
            calculateBorrowingCapacity();
        });

        monthlyDebtSlider.addEventListener('input', function() {
            monthlyDebtInput.value = this.value;
            calculateBorrowingCapacity();
        });

        monthlyDebtInput.addEventListener('input', function() {
            monthlyDebtSlider.value = this.value;
            calculateBorrowingCapacity();
        });

        downPaymentSlider.addEventListener('input', function() {
            downPaymentInput.value = this.value;
            calculateBorrowingCapacity();
        });

        downPaymentInput.addEventListener('input', function() {
            downPaymentSlider.value = this.value;
            calculateBorrowingCapacity();
        });

        borrowingRateSelect.addEventListener('change', calculateBorrowingCapacity);
        borrowingTermSelect.addEventListener('change', calculateBorrowingCapacity);

        // Calculer la capacité d'emprunt
        function calculateBorrowingCapacity() {
            const monthlyIncome = parseFloat(monthlyIncomeSlider.value);
            const monthlyDebt = parseFloat(monthlyDebtSlider.value);
            const downPayment = parseFloat(downPaymentSlider.value);
            const borrowingRate = parseFloat(borrowingRateSelect.value);
            const borrowingTerm = parseInt(borrowingTermSelect.value);

            if (monthlyIncome > 0 && borrowingRate > 0 && borrowingTerm > 0) {
                // On considère généralement qu'on ne doit pas dépasser 33% des revenus pour le remboursement
                const maxDebtPayment = monthlyIncome * 0.33 - monthlyDebt;
                const maxMonthlyPayment = Math.max(0, maxDebtPayment);

                maxMonthlyPaymentElement.textContent = formatXOF(maxMonthlyPayment);

                if (maxMonthlyPayment > 0) {
                    const monthlyRate = borrowingRate / 100 / 12;
                    const numberOfPayments = borrowingTerm * 12;
                    const x = Math.pow(1 + monthlyRate, numberOfPayments);
                    const capacity = (maxMonthlyPayment * (x - 1)) / (monthlyRate * x);

                    borrowingCapacityElement.textContent = formatXOF(capacity + downPayment);
                } else {
                    borrowingCapacityElement.textContent = formatXOF(downPayment);
                }
            }
        }

        // Initialiser les calculs
        calculateMonthlyPayment();
        calculateBorrowingCapacity();
    });
</script>
@endsection
