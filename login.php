<!doctype html>
<html lang="en">
  <head>
    <title>FMS - Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- CSS files -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/main.css">
  </head>
  
  <body>
    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-6 col-lg-8">
            <div class="login-wrap p-4 p-md-5">

              <!-- User Icon -->
              <div class="icon d-flex align-items-center justify-content-center">
                <span id="icon" class="fa fa-user-o"></span>
              </div>

              <!-- Form Title -->
              <h3 class="text-center mb-4">FMS Login</h3>

              <!-- Login Form -->
              <form action="login.php" method="POST" class="login-form">

                <!-- Username Field -->
                <div class="form-group">
                  <label for="username" class="sr-only">Username</label>
                  <input type="text" class="form-control rounded-left" id="username" name="username" aria-label="Username" placeholder="Your Username" required>
                </div>

                <!-- Password Field -->
                <div class="form-group d-flex">
                  <label for="password" class="sr-only">Password</label>
                  <input type="password" class="form-control rounded-left" id="password" name="password" aria-label="Password" placeholder="Your Password" required>
                </div>

                <!-- Forgot Password & Register Link -->
                <div class="form-group d-md-flex">
                  <div class="w-50 text-left">
                    <a href="register.php" class="text-muted">Register</a>
                  </div>
                  <div class="w-50 text-md-right">
                    <a href="#">Forgot Password</a>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                  <input type="submit" class="btn btn-success p-2 px-5" value="Sign In">
                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- JS Files -->
    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/main.js"></script>
  </body>
</html>
