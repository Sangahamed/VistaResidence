@extends('components.front.layouts.auth')

@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Create Account')

@section('content')
    <section class="min-h-screen flex items-stretch text-white bg-gradient-to-br from-gray-900 to-indigo-900">
        <!-- Left Side - Background Image -->
        <div class="lg:flex w-1/2 hidden bg-gray-500 bg-no-repeat bg-cover relative items-center overflow-hidden">
            <div class="absolute bg-gradient-to-b from-black to-transparent opacity-80 inset-0 z-0"></div>
            <div class="w-full px-24 z-10 transform transition-all duration-1000 hover:scale-105">
                <h1 class="text-5xl font-bold text-left tracking-wide mb-6 animate-fade-in">Welcome Aboard</h1>
                <p class="text-2xl my-4 opacity-90 animate-fade-in delay-100">Join our community and unlock exclusive features.</p>
                <div class="mt-10 animate-fade-in delay-200">
                    <div class="h-1 w-20 bg-indigo-500 rounded-full mb-2"></div>
                    <div class="h-1 w-16 bg-indigo-400 rounded-full"></div>
                </div>
            </div>
            
            <!-- Social Icons with Animation -->
            <div class="bottom-0 absolute p-4 text-center right-0 left-0 flex justify-center space-x-6">
                <a href="#" class="social-icon transform hover:scale-125 transition-all duration-300">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                    </svg>
                </a>
                <a href="#" class="social-icon transform hover:scale-125 transition-all duration-300">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                    </svg>
                </a>
                <a href="#" class="social-icon transform hover:scale-125 transition-all duration-300">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Right Side - Form -->
        <div class="lg:w-1/2 w-full flex items-center justify-center text-center md:px-16 px-0 z-0 bg-gray-900 bg-opacity-90 backdrop-blur-sm">
            <div class="absolute lg:hidden z-10 inset-0 bg-gray-500 bg-no-repeat bg-cover items-center bg-gradient-to-br from-gray-900 to-indigo-900">
                <div class="absolute bg-black opacity-60 inset-0 z-0"></div>
            </div>
            
            <div class="w-full py-6 z-20 animate-slide-up">
                <!-- Logo -->
                <div class="mb-10 transform hover:scale-105 transition-all duration-500">
                    <svg viewBox="0 0 247 31" class="w-auto h-8 sm:h-10 inline-flex">
                        <path fill="rgba(99,102,241, .8)" fill-rule="evenodd" clip-rule="evenodd"
                            d="M25.517 0C18.712 0 14.46 3.382 12.758 10.146c2.552-3.382 5.529-4.65 8.931-3.805 1.941.482 3.329 1.882 4.864 3.432 2.502 2.524 5.398 5.445 11.722 5.445 6.804 0 11.057-3.382 12.758-10.145-2.551 3.382-5.528 4.65-8.93 3.804-1.942-.482-3.33-1.882-4.865-3.431C34.736 2.92 31.841 0 25.517 0zM12.758 15.218C5.954 15.218 1.701 18.6 0 25.364c2.552-3.382 5.529-4.65 8.93-3.805 1.942.482 3.33 1.882 4.865 3.432 2.502 2.524 5.397 5.445 11.722 5.445 6.804 0 11.057-3.381 12.758-10.145-2.552 3.382-5.529 4.65-8.931 3.805-1.941-.483-3.329-1.883-4.864-3.432-2.502-2.524-5.398-5.446-11.722-5.446z"
                            fill="#06B6D4"></path>
                        <path fill="#fff" fill-rule="evenodd" clip-rule="evenodd"
                            d="M76.546 12.825h-4.453v8.567c0 2.285 1.508 2.249 4.453 2.106v3.463c-5.962.714-8.332-.928-8.332-5.569v-8.567H64.91V9.112h3.304V4.318l3.879-1.143v5.937h4.453v3.713zM93.52 9.112h3.878v17.849h-3.878v-2.57c-1.365 1.891-3.484 3.034-6.285 3.034-4.884 0-8.942-4.105-8.942-9.389 0-5.318 4.058-9.388 8.942-9.388 2.801 0 4.92 1.142 6.285 2.999V9.112zm-5.674 14.636c3.232 0 5.674-2.392 5.674-5.712s-2.442-5.711-5.674-5.711-5.674 2.392-5.674 5.711c0 3.32 2.442 5.712 5.674 5.712zm16.016-17.313c-1.364 0-2.477-1.142-2.477-2.463a2.475 2.475 0 012.477-2.463 2.475 2.475 0 012.478 2.463c0 1.32-1.113 2.463-2.478 2.463zm-1.939 20.526V9.112h3.879v17.849h-3.879zm8.368 0V.9h3.878v26.06h-3.878zm29.053-17.849h4.094l-5.638 17.849h-3.807l-3.735-12.03-3.771 12.03h-3.806l-5.639-17.849h4.094l3.484 12.315 3.771-12.315h3.699l3.734 12.315 3.52-12.315zm8.906-2.677c-1.365 0-2.478-1.142-2.478-2.463a2.475 2.475 0 012.478-2.463 2.475 2.475 0 012.478 2.463c0 1.32-1.113 2.463-2.478 2.463zm-1.939 20.526V9.112h3.878v17.849h-3.878zm17.812-18.313c4.022 0 6.895 2.713 6.895 7.354V26.96h-3.878V16.394c0-2.713-1.58-4.14-4.022-4.14-2.55 0-4.561 1.499-4.561 5.14v9.567h-3.879V9.112h3.879v2.285c1.185-1.856 3.124-2.749 5.566-2.749zm25.282-6.675h3.879V26.96h-3.879v-2.57c-1.364 1.892-3.483 3.034-6.284 3.034-4.884 0-8.942-4.105-8.942-9.389 0-5.318 4.058-9.388 8.942-9.388 2.801 0 4.92 1.142 6.284 2.999V1.973zm-5.674 21.775c3.232 0 5.674-2.392 5.674-5.712s-2.442-5.711-5.674-5.711-5.674 2.392-5.674 5.711c0 3.32 2.442 5.712 5.674 5.712zm22.553 3.677c-5.423 0-9.481-4.105-9.481-9.389 0-5.318 4.058-9.388 9.481-9.388 3.519 0 6.572 1.82 8.008 4.605l-3.34 1.928c-.79-1.678-2.549-2.749-4.704-2.749-3.16 0-5.566 2.392-5.566 5.604 0 3.213 2.406 5.605 5.566 5.605 2.155 0 3.914-1.107 4.776-2.749l3.34 1.892c-1.508 2.82-4.561 4.64-8.08 4.64zm14.472-13.387c0 3.249 9.661 1.285 9.661 7.89 0 3.57-3.125 5.497-7.003 5.497-3.591 0-6.177-1.607-7.326-4.177l3.34-1.927c.574 1.606 2.011 2.57 3.986 2.57 1.724 0 3.052-.571 3.052-2 0-3.176-9.66-1.391-9.66-7.781 0-3.356 2.909-5.462 6.572-5.462 2.945 0 5.387 1.357 6.644 3.713l-3.268 1.82c-.647-1.392-1.904-2.035-3.376-2.035-1.401 0-2.622.607-2.622 1.892zm16.556 0c0 3.249 9.66 1.285 9.66 7.89 0 3.57-3.124 5.497-7.003 5.497-3.591 0-6.176-1.607-7.326-4.177l3.34-1.927c.575 1.606 2.011 2.57 3.986 2.57 1.724 0 3.053-.571 3.053-2 0-3.176-9.66-1.391-9.66-7.781 0-3.356 2.908-5.462 6.572-5.462 2.944 0 5.386 1.357 6.643 3.713l-3.268 1.82c-.646-1.392-1.903-2.035-3.375-2.035-1.401 0-2.622.607-2.622 1.892z"
                            fill="#000"></path>
                    </svg>
                </div>

                <!-- Social Login Options -->
                <div class="mb-8 space-y-4">
                    <p class="text-gray-300 text-sm uppercase tracking-wider">Sign up with</p>
                    <div class="flex justify-center space-x-4">
                        <button class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                            <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-indigo-400 text-indigo-400 hover:bg-indigo-400 hover:text-white">
                                <i class="fab fa-facebook-f"></i>
                            </span>
                        </button>
                        <button class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                            <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-red-400 text-red-400 hover:bg-red-400 hover:text-white">
                                <i class="fab fa-google"></i>
                            </span>
                        </button>
                        <button class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                            <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-blue-400 text-blue-400 hover:bg-blue-400 hover:text-white">
                                <i class="fab fa-linkedin-in"></i>
                            </span>
                        </button>
                    </div>
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-gray-900 text-gray-400">Or continue with</span>
                        </div>
                    </div>
                </div>

                <!-- Error Message -->
                @if (session('fail'))
                    <div class="mb-6 p-4 rounded-lg bg-red-900/50 border border-red-500 text-red-200 animate-shake">
                        {{ session('fail') }}
                    </div>
                @endif

                <!-- Registration Form -->
                <form id="registerForm" action="{{ route('register') }}" method="POST" class="sm:w-2/3 w-full px-4 lg:px-0 mx-auto">
                    @csrf
                    
                    <!-- Name Field -->
                    <div class="mb-4 relative">
                        <div class="flex items-center">
                            <input type="text" name="name" value="{{ old('name') }}" id="name"
                                placeholder="Full Name" 
                                class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                                autocomplete="name" autofocus>
                            <i id="name-icon" class="absolute right-4 text-xl opacity-0 transition-all duration-300"></i>
                        </div>
                        <span id="name-error" class="text-red-400 text-xs mt-1 hidden">Please enter a valid name (min 3 characters)</span>
                    </div>

                    <!-- Phone Field -->
                    <div class="mb-4 relative">
                        <input type="tel" name="phone" value="{{ old('phone') }}" id="phone"
                            placeholder="Phone Number" 
                            class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                            autocomplete="tel">
                        <i id="phone-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                        <span id="phone-error" class="text-red-400 text-xs mt-1 hidden">Please enter a valid phone number</span>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4 relative">
                        <input type="email" name="email" value="{{ old('email') }}" id="email"
                            placeholder="Email Address" 
                            class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                            autocomplete="email">
                        <i id="email-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                        <span id="email-error" class="text-red-400 text-xs mt-1 hidden">Please enter a valid email address</span>
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4 relative">
                        <input type="password" name="password" id="password"
                            placeholder="Password" 
                            class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                            autocomplete="new-password">
                        <i id="password-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                        <span id="password-error" class="text-red-400 text-xs mt-1 hidden">Password must be at least 6 characters</span>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="mb-6 relative">
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            placeholder="Confirm Password" 
                            class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                            autocomplete="new-password">
                        <i id="confirm-password-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                        <span id="confirm-password-error" class="text-red-400 text-xs mt-1 hidden">Passwords must match</span>
                    </div>

                    <!-- Submit Button -->
                    <div class="px-4 pb-2 pt-4">
                        <button type="submit" id="submit-btn"
                            class="relative overflow-hidden uppercase block w-full p-4 text-lg rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-indigo-500/30">
                            <span id="btn-text">Create Account</span>
                            <div id="btn-loader" class="hidden absolute inset-0 flex items-center justify-center">
                                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6"></div>
                            </div>
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="mt-8 text-center">
                        <p class="text-gray-400">Already have an account? 
                            <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors duration-300">Sign in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Custom CSS for Animations -->
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        .animate-fade-in.delay-100 {
            animation-delay: 0.1s;
        }
        
        .animate-fade-in.delay-200 {
            animation-delay: 0.2s;
        }
        
        .animate-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        .animate-slide-up {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .loader {
            border-top-color: #6366f1;
            animation: spin 1s linear infinite;
        }
        
        .social-icon {
            transition: all 0.3s ease;
            color: rgba(255,255,255,0.7);
        }
        
        .social-icon:hover {
            color: white;
            transform: translateY(-3px);
        }
        
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        
        .fa-check-circle {
            color: #10B981;
        }
        
        .fa-exclamation-circle {
            color: #EF4444;
        }
    </style>

    <!-- Enhanced JavaScript Validation -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnLoader = document.getElementById('btn-loader');
            
            // Form elements
            const nameInput = document.getElementById('name');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            // Icons
            const nameIcon = document.getElementById('name-icon');
            const phoneIcon = document.getElementById('phone-icon');
            const emailIcon = document.getElementById('email-icon');
            const passwordIcon = document.getElementById('password-icon');
            const confirmPasswordIcon = document.getElementById('confirm-password-icon');
            
            // Error messages
            const nameError = document.getElementById('name-error');
            const phoneError = document.getElementById('phone-error');
            const emailError = document.getElementById('email-error');
            const passwordError = document.getElementById('password-error');
            const confirmPasswordError = document.getElementById('confirm-password-error');
            
            // Validation patterns
            const namePattern = /^[a-zA-Z\s]{3,}$/;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phonePattern = /^[0-9\s\+\-\(\)]{10,15}$/;
            
            // Validate on input
            nameInput.addEventListener('input', validateName);
            phoneInput.addEventListener('input', validatePhone);
            emailInput.addEventListener('input', validateEmail);
            passwordInput.addEventListener('input', validatePassword);
            confirmPasswordInput.addEventListener('input', validateConfirmPassword);
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Run all validations
                validateName();
                validatePhone();
                validateEmail();
                validatePassword();
                validateConfirmPassword();
                
                // Check if all fields are valid
                const isValid = !nameError.classList.contains('block') && 
                              !phoneError.classList.contains('block') && 
                              !emailError.classList.contains('block') && 
                              !passwordError.classList.contains('block') && 
                              !confirmPasswordError.classList.contains('block');
                
                if (isValid) {
                    // Show loading state
                    btnText.classList.add('opacity-0');
                    btnLoader.classList.remove('hidden');
                    
                    // Simulate form submission (replace with actual AJAX call if needed)
                    setTimeout(() => {
                        form.submit();
                    }, 1500);
                } else {
                    // Show error state briefly
                    submitBtn.classList.add('bg-red-600');
                    setTimeout(() => {
                        submitBtn.classList.remove('bg-red-600');
                    }, 1000);
                }
            });
            
            // Validation functions
            function validateName() {
                if (nameInput.value.length >= 3 && namePattern.test(nameInput.value)) {
                    setValid(nameInput, nameIcon, nameError);
                } else {
                    setInvalid(nameInput, nameIcon, nameError, 'Please enter a valid name (min 3 characters)');
                }
            }
            
            function validatePhone() {
                if (phonePattern.test(phoneInput.value)) {
                    setValid(phoneInput, phoneIcon, phoneError);
                } else {
                    setInvalid(phoneInput, phoneIcon, phoneError, 'Please enter a valid phone number');
                }
            }
            
            function validateEmail() {
                if (emailPattern.test(emailInput.value)) {
                    setValid(emailInput, emailIcon, emailError);
                } else {
                    setInvalid(emailInput, emailIcon, emailError, 'Please enter a valid email address');
                }
            }
            
            function validatePassword() {
                if (passwordInput.value.length >= 6) {
                    setValid(passwordInput, passwordIcon, passwordError);
                    
                    // Also validate confirmation if it has value
                    if (confirmPasswordInput.value) {
                        validateConfirmPassword();
                    }
                } else {
                    setInvalid(passwordInput, passwordIcon, passwordError, 'Password must be at least 6 characters');
                }
            }
            
            function validateConfirmPassword() {
                if (confirmPasswordInput.value === passwordInput.value && passwordInput.value.length >= 6) {
                    setValid(confirmPasswordInput, confirmPasswordIcon, confirmPasswordError);
                } else {
                    setInvalid(confirmPasswordInput, confirmPasswordIcon, confirmPasswordError, 'Passwords must match');
                }
            }
            
            // Helper functions
            function setValid(input, icon, errorElement) {
                input.classList.remove('border-red-500');
                input.classList.add('border-green-500');
                icon.className = 'fas fa-check-circle text-green-500 absolute right-4 top-4 text-xl opacity-100 transition-all duration-300';
                errorElement.classList.remove('block');
                errorElement.classList.add('hidden');
            }
            
            function setInvalid(input, icon, errorElement, message) {
                input.classList.remove('border-green-500');
                input.classList.add('border-red-500');
                icon.className = 'fas fa-exclamation-circle text-red-500 absolute right-4 top-4 text-xl opacity-100 transition-all duration-300';
                errorElement.textContent = message;
                errorElement.classList.remove('hidden');
                errorElement.classList.add('block');
            }
            
            // Initialize Font Awesome (if not already loaded)
            if (!document.querySelector('script[src*="fontawesome"]')) {
                const faScript = document.createElement('script');
                faScript.src = 'https://kit.fontawesome.com/a076d05399.js';
                faScript.crossOrigin = 'anonymous';
                document.head.appendChild(faScript);
            }
        });
    </script>
@endsection