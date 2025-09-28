<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PSCTV - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
    }
    /* Navbar */
    .navbar {
      background-color: #fff !important; /* White */
    }
    .navbar .nav-link, 
    .navbar .navbar-brand {
      color: #006400 !important; /* Dark green text */
      font-weight: 600;
    }
    .navbar .nav-link:hover, 
    .navbar .dropdown-menu a:hover {
      color: #fff !important;
      background-color: #006400 !important;
    }
    .dropdown-menu {
      border-radius: 0;
    }
    .btn-login {
      background: #90ee90; /* Light green */
      color: #006400;
      border-radius: 20px;
      font-weight: 600;
      padding: 6px 20px;
      transition: 0.3s;
      border: none;
    }
    .btn-login:hover {
      background: #006400;
      color: #fff;
    }
    /* Carousel */
    .carousel-item {
      height: 90vh;
      min-height: 400px;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    .carousel-item::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.6);
    }
    .carousel-caption {
      position: absolute;
      left: 10%;
      bottom: 20%;
      text-align: left;
      z-index: 2;
    }
    .carousel-caption h1 {
      font-size: 3rem;
      font-weight: 700;
      color: #fff;
    }
    .carousel-caption p {
      font-size: 1.2rem;
      margin: 15px 0;
      color: #fff;
    }
    .btn-upgrade {
      background: #90ee90; /* Light green */
      color: #006400;
      font-weight: bold;
      padding: 10px 25px;
      border-radius: 30px;
      transition: 0.3s;
      border: none;
    }
    .btn-upgrade:hover {
      background: #006400;
      color: #fff;
    }
    /* Section */
    .plans {
      padding: 60px 20px;
      text-align: center;
    }
    .plans h2 {
      font-weight: 700;
      margin-bottom: 20px;
      color: #006400;
    }
    .plans p {
      font-size: 1.1rem;
      margin-bottom: 40px;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg shadow-sm fixed-top">
    <div class="container-fluid px-4">
      <a class="navbar-brand" href="{{ route('landing') }}">
        <img src="/images/logo.png" alt="PSCTV Logo" height="40" class="me-2">
        <span>PSCTV</span>
      </a>
      <button class="navbar-toggler text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Internet</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Fiber Plans</a></li>
              <li><a class="dropdown-item" href="#">Prepaid Plans</a></li>
              <li><a class="dropdown-item" href="#">Bundles</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Services</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Cable TV</a></li>
              <li><a class="dropdown-item" href="#">Streaming</a></li>
              <li><a class="dropdown-item" href="#">Support</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ route('login.form') }}" class="btn btn-login ms-3">Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Carousel -->
  <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <!-- Slide 1 -->
      <div class="carousel-item active" style="background-image: url('https://image.tmdb.org/t/p/original/8rpDcsfLJypbO6vREc0547VKqEv.jpg');">
        <div class="carousel-caption">
          <h1>Dune: Part Two</h1>
          <p>Epic sci-fi continues. Stream now on PSCTV.</p>
          <a href="{{ route('login.form') }}" class="btn btn-upgrade">WATCH NOW</a>
        </div>
      </div>
      <!-- Slide 2 -->
      <div class="carousel-item" style="background-image: url('https://image.tmdb.org/t/p/original/rinDx6Y2W0fXwlHTtzN7xC9Qf4.jpg');">
        <div class="carousel-caption">
          <h1>Oppenheimer</h1>
          <p>The story of the man behind the bomb. Available now.</p>
          <a href="{{ route('login.form') }}" class="btn btn-upgrade">WATCH NOW</a>
        </div>
      </div>
      <!-- Slide 3 -->
      <div class="carousel-item" style="background-image: url('https://image.tmdb.org/t/p/original/8pjWz2lt29KyVGoq1mXYu6Br7dE.jpg');">
        <div class="carousel-caption">
          <h1>Barbie</h1>
          <p>Step into the world of Barbie, streaming now on PSCTV.</p>
          <a href="{{ route('login.form') }}" class="btn btn-upgrade">WATCH NOW</a>
        </div>
      </div>
    </div>
    <!-- Controls -->
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
    </button>
  </div>

  <!-- Plans Section -->
  <section class="plans">
    <h2>Find the Best Plan for You</h2>
    <p>Choose from our affordable and flexible packages tailored for your needs.</p>
    <div class="row justify-content-center">
      <div class="col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">Basic Plan</h5>
            <p class="card-text">Affordable internet for small families.</p>
            <button class="btn btn-upgrade">Select</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">Standard Plan</h5>
            <p class="card-text">Balanced speed and entertainment options.</p>
            <button class="btn btn-upgrade">Select</button>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <h5 class="card-title">Premium Plan</h5>
            <p class="card-text">Unlimited speed with full cable & streaming.</p>
            <button class="btn btn-upgrade">Select</button>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
