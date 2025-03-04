<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Housing Reconstruction Unit</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">
    @include('dashboard.layout.css')
</head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .login-box {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .textbox {
        margin-bottom: 15px;
    }

    .textbox label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .textbox input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        outline: none;
    }

    .textbox input:focus {
        border-color: #007bff;
    }

    .btn {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 4px;
        background-color: #007bff;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .forgot {
        display: block;
        text-align: center;
        margin-top: 10px;
        color: #007bff;
        text-decoration: none;
    }

    .forgot:hover {
        text-decoration: underline;
    }
</style>


<body>
    <div class="container">
        <div class="login-box">
            <h1>Login</h1>
            <form method="POST" action="{{ route('customLogin') }}">
                @csrf
                <div class="textbox">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="textbox">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <button type="submit" class="btn">Login</button>
                <!--<a href="#" class="forgot">Forgot Password?</a>-->
            </form>
        </div>
    </div>

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                toast: true,         // This enables the toast mode
                position: 'top-end', // Position of the toast
                showConfirmButton: false, // Hides the confirm button
                timer: 3000          // Time to show the toast in milliseconds
            });
        </script>
    @endif
    @if(session('success'))
        <script>

            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: "{{ session('success') }}"
            });
        </script>
    @endif
</body>



</html>