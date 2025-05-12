<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Calculateur de prêt immobilier</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite('resources/css/app.css')
    @livewireStyles
    @stack('stylesheets')
    
    <style>
        .animate-slide-up {
            animation: slideUp 0.3s ease-out forwards;
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .range-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
            margin-top: -8px;
        }
        
        .range-thumb::-moz-range-thumb {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #3b82f6;
            cursor: pointer;
        }
        
        .range-track {
            -webkit-appearance: none;
            width: 100%;
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
        }
        
        .range-track::-webkit-slider-runnable-track {
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
        }
        
        .range-track::-moz-range-track {
            height: 4px;
            border-radius: 2px;
            background: #e5e7eb;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header Section -->
            <div class="mb-8 animate-slide-up">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Calculateur de prêt immobilier</h1>
                <p class="text-gray-600">Estimez vos mensualités ou votre capacité d'emprunt pour votre projet immobilier.</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <ul class="flex flex-wrap -mb-px" id="calculatorTabs" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="monthly-tab" data-tabs-target="#monthly" type="button" role="tab" aria-controls="monthly" aria-selected="true">
                            Calculateur de mensualités
                        </button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="capacity-tab" data-tabs-target="#capacity" type="button" role="tab" aria-controls="capacity" aria-selected="false">
                            Capacité d'emprunt
                        </button>
                    </li>
                </ul>
            </div>

            <!-- Tabs Content -->
            <div id="calculatorTabsContent">
                <!-- Monthly Payment Calculator -->
                <div class="p-4 rounded-lg bg-white shadow-md mb-6 animate-fade-in" id="monthly" role="tabpanel" aria-labelledby="monthly-tab">
                    <h2 class="text-xl font-semibold mb-4">Calculez vos mensualités</h2>
                    
                    <div class="space-y-6">
                        <!-- Loan Amount -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="loan-amount" class="block text-sm font-medium text-gray-700">Montant du prêt</label>
                                <div class="relative w-32">
                                    <input type="number" id="loan-amount-input" class="block w-full rounded-md border-gray-300 pl-3 pr-12 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="10000000">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">XOF</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="loan-amount" min="1000000" max="300000000" step="500000" value="10000000">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>1 000 000 XOF</span>
                                <span>300 000 000 XOF</span>
                            </div>
                        </div>

                        <!-- Interest Rate -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="interest-rate" class="block text-sm font-medium text-gray-700">Taux d'intérêt</label>
                                <div class="relative w-24">
                                    <input type="number" id="interest-rate-input" class="block w-full rounded-md border-gray-300 pl-3 pr-8 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="5.5" step="0.1">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">%</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="interest-rate" min="0.1" max="15" step="0.1" value="5.5">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0.1%</span>
                                <span>15%</span>
                            </div>
                        </div>

                        <!-- Loan Term -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="loan-term" class="block text-sm font-medium text-gray-700">Durée du prêt</label>
                                <div class="relative w-24">
                                    <input type="number" id="loan-term-input" class="block w-full rounded-md border-gray-300 pl-3 pr-8 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="15">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">ans</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="loan-term" min="1" max="30" step="1" value="15">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>1 an</span>
                                <span>30 ans</span>
                            </div>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center animate-slide-up" style="animation-delay: 0.1s">
                            <p class="text-sm text-blue-600 mb-1">Mensualité</p>
                            <p id="monthly-payment" class="text-2xl font-bold text-blue-800">0 XOF</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center animate-slide-up" style="animation-delay: 0.2s">
                            <p class="text-sm text-blue-600 mb-1">Coût total des intérêts</p>
                            <p id="total-interest" class="text-2xl font-bold text-blue-800">0 XOF</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg text-center animate-slide-up" style="animation-delay: 0.3s">
                            <p class="text-sm text-blue-600 mb-1">Coût total du crédit</p>
                            <p id="total-cost" class="text-2xl font-bold text-blue-800">0 XOF</p>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r-lg animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-yellow-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Ce calculateur fournit une estimation. Les taux et conditions réels peuvent varier selon votre profil, votre banque et les conditions du marché.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Amortization Table -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tableau d'amortissement</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Année</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capital remboursé</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intérêts payés</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capital restant</th>
                                    </tr>
                                </thead>
                                <tbody id="amortization-table" class="bg-white divide-y divide-gray-200">
                                    <!-- Will be filled by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Borrowing Capacity Calculator -->
                <div class="hidden p-4 rounded-lg bg-white shadow-md mb-6 animate-fade-in" id="capacity" role="tabpanel" aria-labelledby="capacity-tab">
                    <h2 class="text-xl font-semibold mb-4">Calculez votre capacité d'emprunt</h2>
                    
                    <div class="space-y-6">
                        <!-- Monthly Income -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="monthly-income" class="block text-sm font-medium text-gray-700">Revenus mensuels nets</label>
                                <div class="relative w-32">
                                    <input type="number" id="monthly-income-input" class="block w-full rounded-md border-gray-300 pl-3 pr-12 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="500000">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">XOF</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="monthly-income" min="100000" max="5000000" step="50000" value="500000">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>100 000 XOF</span>
                                <span>5 000 000 XOF</span>
                            </div>
                        </div>

                        <!-- Monthly Debt -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="monthly-debt" class="block text-sm font-medium text-gray-700">Charges mensuelles</label>
                                <div class="relative w-32">
                                    <input type="number" id="monthly-debt-input" class="block w-full rounded-md border-gray-300 pl-3 pr-12 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="100000">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">XOF</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="monthly-debt" min="0" max="1000000" step="10000" value="100000">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0 XOF</span>
                                <span>1 000 000 XOF</span>
                            </div>
                        </div>

                        <!-- Down Payment -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <label for="down-payment" class="block text-sm font-medium text-gray-700">Apport personnel</label>
                                <div class="relative w-32">
                                    <input type="number" id="down-payment-input" class="block w-full rounded-md border-gray-300 pl-3 pr-12 py-2 text-right shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" value="2000000">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">XOF</span>
                                    </div>
                                </div>
                            </div>
                            <input type="range" class="w-full range-track range-thumb" id="down-payment" min="0" max="50000000" step="1000000" value="2000000">
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>0 XOF</span>
                                <span>50 000 000 XOF</span>
                            </div>
                        </div>

                        <!-- Rate and Term -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="borrowing-rate" class="block text-sm font-medium text-gray-700">Taux d'intérêt</label>
                                <select id="borrowing-rate" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                                    <option value="4.5">4.5 %</option>
                                    <option value="5.0">5.0 %</option>
                                    <option value="5.5" selected>5.5 %</option>
                                    <option value="6.0">6.0 %</option>
                                    <option value="6.5">6.5 %</option>
                                    <option value="7.0">7.0 %</option>
                                </select>
                            </div>
                            <div>
                                <label for="borrowing-term" class="block text-sm font-medium text-gray-700">Durée du prêt</label>
                                <select id="borrowing-term" class="mt-1 block w-full rounded-md border-gray-300 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm">
                                    <option value="10">10 ans</option>
                                    <option value="15" selected>15 ans</option>
                                    <option value="20">20 ans</option>
                                    <option value="25">25 ans</option>
                                    <option value="30">30 ans</option>
                                    <option value="35">35 ans</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Results -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-green-50 p-4 rounded-lg text-center animate-slide-up" style="animation-delay: 0.1s">
                            <p class="text-sm text-green-600 mb-1">Capacité d'emprunt maximale</p>
                            <p id="borrowing-capacity" class="text-2xl font-bold text-green-800">0 XOF</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center animate-slide-up" style="animation-delay: 0.2s">
                            <p class="text-sm text-green-600 mb-1">Mensualité maximale</p>
                            <p id="max-monthly-payment" class="text-2xl font-bold text-green-800">0 XOF</p>
                            <p class="text-xs text-green-500 mt-1">(33% de vos revenus nets - vos charges actuelles)</p>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg animate-fade-in">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-information-line text-blue-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Cette estimation est basée sur un taux d'endettement maximal de 33% de vos revenus nets. Les banques peuvent appliquer des critères différents selon votre profil et leur politique de risque.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Options -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-full">
                                    <i class="ri-calendar-line text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900">Prendre rendez-vous</h3>
                                    <p class="mt-1 text-sm text-gray-500">Rencontrez un conseiller pour étudier votre projet en détail.</p>
                                    <button class="mt-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Prendre rendez-vous
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 bg-blue-100 p-2 rounded-full">
                                    <i class="ri-phone-line text-blue-600 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-medium text-gray-900">Être rappelé</h3>
                                    <p class="mt-1 text-sm text-gray-500">Un conseiller vous contactera dans les plus brefs délais.</p>
                                    <button class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Demander à être rappelé
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Format XOF currency
            function formatXOF(value) {
                return new Intl.NumberFormat('fr-FR', { 
                    style: 'currency', 
                    currency: 'XOF',
                    maximumFractionDigits: 0
                }).format(value);
            }

            // Tab functionality
            const tabs = document.querySelectorAll('[data-tabs-target]');
            const tabContents = document.querySelectorAll('[role="tabpanel"]');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = document.querySelector(tab.dataset.tabsTarget);
                    
                    // Hide all tab contents
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    
                    // Show selected tab content
                    target.classList.remove('hidden');
                    
                    // Update active tab
                    tabs.forEach(t => {
                        t.classList.remove('border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                    });
                    
                    tab.classList.add('border-blue-500', 'text-blue-600');
                    tab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                });
            });

            // Monthly Payment Calculator
            const loanAmountSlider = document.getElementById('loan-amount');
            const loanAmountInput = document.getElementById('loan-amount-input');
            const interestRateSlider = document.getElementById('interest-rate');
            const interestRateInput = document.getElementById('interest-rate-input');
            const loanTermSlider = document.getElementById('loan-term');
            const loanTermInput = document.getElementById('loan-term-input');
            const monthlyPaymentElement = document.getElementById('monthly-payment');
            const totalInterestElement = document.getElementById('total-interest');
            const totalCostElement = document.getElementById('total-cost');
            const amortizationTableBody = document.getElementById('amortization-table');

            // Sync sliders and inputs
            function syncInputs() {
                loanAmountInput.value = loanAmountSlider.value;
                interestRateInput.value = interestRateSlider.value;
                loanTermInput.value = loanTermSlider.value;
                calculateMonthlyPayment();
            }

            loanAmountSlider.addEventListener('input', syncInputs);
            interestRateSlider.addEventListener('input', syncInputs);
            loanTermSlider.addEventListener('input', syncInputs);

            loanAmountInput.addEventListener('input', function() {
                loanAmountSlider.value = this.value;
                calculateMonthlyPayment();
            });

            interestRateInput.addEventListener('input', function() {
                interestRateSlider.value = this.value;
                calculateMonthlyPayment();
            });

            loanTermInput.addEventListener('input', function() {
                loanTermSlider.value = this.value;
                calculateMonthlyPayment();
            });

            // Calculate monthly payment
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

            // Update amortization table
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
                    row.className = 'hover:bg-gray-50';
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${year}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatXOF(yearlyCapitalPaid)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatXOF(yearlyInterestPaid)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatXOF(remainingCapital)}</td>
                    `;
                    amortizationTableBody.appendChild(row);
                }

                if (loanTerm > 10) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                            <i class="ri-arrow-down-line"></i>
                            <span class="ml-2">Tableau limité aux 10 premières années</span>
                        </td>
                    `;
                    amortizationTableBody.appendChild(row);
                }
            }

            // Borrowing Capacity Calculator
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

            // Sync inputs for borrowing capacity
            function syncBorrowingInputs() {
                monthlyIncomeInput.value = monthlyIncomeSlider.value;
                monthlyDebtInput.value = monthlyDebtSlider.value;
                downPaymentInput.value = downPaymentSlider.value;
                calculateBorrowingCapacity();
            }

            monthlyIncomeSlider.addEventListener('input', syncBorrowingInputs);
            monthlyDebtSlider.addEventListener('input', syncBorrowingInputs);
            downPaymentSlider.addEventListener('input', syncBorrowingInputs);

            monthlyIncomeInput.addEventListener('input', function() {
                monthlyIncomeSlider.value = this.value;
                calculateBorrowingCapacity();
            });

            monthlyDebtInput.addEventListener('input', function() {
                monthlyDebtSlider.value = this.value;
                calculateBorrowingCapacity();
            });

            downPaymentInput.addEventListener('input', function() {
                downPaymentSlider.value = this.value;
                calculateBorrowingCapacity();
            });

            borrowingRateSelect.addEventListener('change', calculateBorrowingCapacity);
            borrowingTermSelect.addEventListener('change', calculateBorrowingCapacity);

            // Calculate borrowing capacity
            function calculateBorrowingCapacity() {
                const monthlyIncome = parseFloat(monthlyIncomeSlider.value);
                const monthlyDebt = parseFloat(monthlyDebtSlider.value);
                const downPayment = parseFloat(downPaymentSlider.value);
                const borrowingRate = parseFloat(borrowingRateSelect.value);
                const borrowingTerm = parseInt(borrowingTermSelect.value);

                if (monthlyIncome > 0 && borrowingRate > 0 && borrowingTerm > 0) {
                    // Max debt payment is 33% of income minus existing debts
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

            // Initialize calculations
            calculateMonthlyPayment();
            calculateBorrowingCapacity();
        });
    </script>
</body>
</html>