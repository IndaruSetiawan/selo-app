
<div class="container w-100 mx-auto g-0 position-relative rounded-5 overflow-hidden">
        <div class="form-box login d-flex p-5 align-items-center text-center position-absolute w-50 h-100">
            <form action="/admin/login" method="post" class="w-100">
                <h1 class="my-1">Login</h1>
                <div class="input-box position-relative my-4">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="email" type="text" placeholder="Email" required>
                    <i class="bi bi-envelope-at-fill position-absolute"></i>
                </div>
                <div class="input-box position-relative my-3">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="password" type="password" placeholder="Password" required>
                    <i class="bi bi-key-fill position-absolute"></i>
                </div>
                <div class="forgot-link my-1 mb-2">
                    <a href="#" class="text-decoration-none">Forgot Password</a>
                </div>
                <button type="submit" class="btn w-100 rounded-3 cursor-pointer">Login</button>
                <p class="mt-2 mb-2 my-2">Or login with</p>
                <div class="google-btn d-flex justify-content-center align-items-center text-center rounded-3 mx-auto cursor-pointer">
                    <a href="/admin/google" class="text-decoration-none"><i class="bi bi-google p-2 text-white"></i>Google</a>
                </div>
            </form>
        </div>

        <div class="form-box register d-flex p-5 align-items-center text-center position-absolute w-50 h-100">            
            <form action="/admin/register" method="post" class="w-100">
                <h1 class="my-1">Register</h1>
                <div class="input-box position-relative my-4">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="name" type="text" placeholder="Name" required>
                    <i class="bi bi-person-fill position-absolute"></i>
                </div>
                <div class="input-box position-relative my-4">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="phone" type="tel" placeholder="Telephone" required>
                    <i class="bi bi-telephone-fill position-absolute"></i>
                </div>
                <div class="input-box position-relative my-4">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="email" type="email" placeholder="Email" required>
                    <i class="bi bi-envelope-at-fill position-absolute"></i>
                </div>
                <div class="input-box position-relative my-4">
                    <input class="w-100 py-2 pe-5 ps-3 rounded-3 border-0" name="password" type="password" placeholder="Password" required>
                    <i class="bi bi-key-fill position-absolute"></i>
                </div>
                <button type="submit" class="btn w-100 rounded-3 cursor-pointer">Register</button>
                <p class="mb-2 my-2">Already registered? Log in with</p>
                <div class="google-btn d-flex justify-content-center align-items-center text-center rounded-3 mx-auto cursor-pointer">
                    <a href="/admin/google" class="text-decoration-none"><i class="bi bi-google p-2"></i>Google</a>
                </div>
            </form>
        </div>

        <div class="toggle-box w-100 h-100 position-absolute">
            <div class="toggle-panel toggle-left position-absolute w-50 h-100 d-flex flex-column justify-content-center align-items-center">
                <img src="/assets/admin/img/selo.svg" alt="selo-logo" class="logo-login">
                <h1>Hello, Welcome!</h1>
                <p class="mb-2">Don't have an account?</p>
                <button class="btn btn-register rounded-3 text-center cursor-pointer">Register</button>
            </div>
            <div class="toggle-panel toggle-right position-absolute w-50 h-100 d-flex flex-column justify-content-center align-items-center">
                <img src="/assets/admin/img/selo.svg" alt="selo-logo" class="logo-register">
                <h1>Welcome Back!</h1>
                <p class="mb-2">Already have an account?</p>
                <button class="btn btn-login rounded-3 text-center cursor-pointer">Login</button>
            </div>
        </div>
    </div>