<?php $__env->startSection('pageTitle', isset($pageTitle) ? $pageTitle : 'Login'); ?>

<?php $__env->startSection('content'); ?>
<section class="min-h-screen flex items-stretch text-white bg-gradient-to-br from-gray-900 to-indigo-900">
    <!-- Left Side - Background Image -->
    <div class="lg:flex w-1/2 hidden bg-gray-500 bg-no-repeat bg-cover relative items-center overflow-hidden">
        <div class="absolute bg-gradient-to-b from-black to-transparent opacity-80 inset-0 z-0"></div>
        <div class="w-full px-24 z-10 transform transition-all duration-1000 hover:scale-105">
            <h1 class="text-5xl font-bold text-left tracking-wide mb-6 animate-fade-in">Welcome Back</h1>
            <p class="text-2xl my-4 opacity-90 animate-fade-in delay-100">Sign in to access your personalized dashboard.</p>
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
        
        <div class="w-full py-8 z-20 animate-slide-up">
            <!-- Logo -->
            <div class="mb-10 transform hover:scale-105 transition-all duration-500">
                
            </div>

            <!-- Social Login Options -->
            <div class="mb-8 space-y-4">
                <p class="text-gray-300 text-sm uppercase tracking-wider">Sign in with</p>
                <div class="flex justify-center space-x-4">
                    <a href="<?php echo e(route('social.login', 'facebook')); ?>" class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                        <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-indigo-400 text-indigo-400 hover:bg-indigo-400 hover:text-white">
                            <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </a>
                    <a href="<?php echo e(route('social.login', 'google')); ?>" class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                        <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-red-400 text-red-400 hover:bg-red-400 hover:text-white">
                            <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" class="w-5 h-5">
                                <path d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 5.139c1.453 0 2.838.355 4.045 1.006 1.997-1.3 2.832-1.032 2.832-1.032.545 1.378.203 2.398.099 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </a>
                    <a href="#" class="social-btn transform hover:-translate-y-1 transition-all duration-300">
                        <span class="w-10 h-10 items-center justify-center inline-flex rounded-full font-bold text-lg border-2 border-blue-400 text-blue-400 hover:bg-blue-400 hover:text-white">
                            <svg fill="currentColor" viewBox="0 0 20 20" aria-hidden="true" class="w-5 h-5">
                                <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                            </svg>
                        </span>
                    </a>
                </div>
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-900 text-gray-400">Or sign in with email</span>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            

            <!-- Login Form -->
            <form id="loginForm" action="<?php echo e(route('login')); ?>" method="POST" class="sm:w-2/3 w-full px-4 lg:px-0 mx-auto">
                <?php echo csrf_field(); ?>
                
                <!-- Email Field -->
                <div class="mb-4 relative">
                    <input type="email" name="email" id="email" placeholder="Email Address" 
                        class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                        value="<?php echo e(old('email')); ?>"
                        autocomplete="email"
                        autofocus>
                    <i id="email-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                    <span id="email-error" class="text-red-400 text-xs mt-1 hidden">Please enter a valid email address</span>
                </div>

                <!-- Password Field -->
                <div class="mb-4 relative">
                    <input type="password" name="password" id="password"
                        placeholder="Password" 
                        class="form-input block w-full p-4 text-lg rounded-lg bg-gray-800 border border-gray-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-900 transition-all duration-300"
                        autocomplete="current-password">
                    <i id="password-icon" class="absolute right-4 top-4 text-xl opacity-0 transition-all duration-300"></i>
                    <span id="password-error" class="text-red-400 text-xs mt-1 hidden">Password must be at least 6 characters</span>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-700 rounded bg-gray-700 transition-all duration-300">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-300 hover:text-gray-100 cursor-pointer transition-colors duration-300">
                            Remember me
                        </label>
                    </div>
                    <div class="text-sm">
                        <a href="" class="font-medium text-indigo-400 hover:text-indigo-300 transition-colors duration-300">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="px-4 pb-2 pt-4">
                    <button type="submit" id="submit-btn"
                        class="relative overflow-hidden uppercase block w-full p-4 text-lg rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-300 transform hover:-translate-y-1 shadow-lg hover:shadow-indigo-500/30">
                        <span id="btn-text">Sign In</span>
                        <div id="btn-loader" class="hidden absolute inset-0 flex items-center justify-center">
                            <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-6 w-6"></div>
                        </div>
                    </button>
                </div>

                <!-- Register Link -->
                <div class="mt-8 text-center">
                    <p class="text-gray-400">Don't have an account? 
                        <a href="<?php echo e(route('register')); ?>" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors duration-300">Sign up</a>
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
        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnLoader = document.getElementById('btn-loader');
        
        // Form elements
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        // Icons
        const emailIcon = document.getElementById('email-icon');
        const passwordIcon = document.getElementById('password-icon');
        
        // Error messages
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        
        // Validation patterns
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        // Validate on input
        emailInput.addEventListener('input', validateEmail);
        passwordInput.addEventListener('input', validatePassword);
        
        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Run all validations
            validateEmail();
            validatePassword();
            
            // Check if all fields are valid
            const isValid = !emailError.classList.contains('block') && 
                          !passwordError.classList.contains('block');
            
            if (isValid) {
                // Show loading state
                btnText.classList.add('opacity-0');
                btnLoader.classList.remove('hidden');
                
                // Submit the form
                setTimeout(() => {
                    form.submit();
                }, 1000);
            } else {
                // Show error state briefly
                submitBtn.classList.add('bg-red-600');
                setTimeout(() => {
                    submitBtn.classList.remove('bg-red-600');
                }, 1000);
            }
        });
        
        // Validation functions
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
            } else {
                setInvalid(passwordInput, passwordIcon, passwordError, 'Password must be at least 6 characters');
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.front.layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\VistaResidence\resources\views/front/auth/login.blade.php ENDPATH**/ ?>