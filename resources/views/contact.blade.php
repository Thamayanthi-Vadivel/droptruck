<!DOCTYPE html>
<html>

<head>
  <!-- Basic -->
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Mobile Metas -->
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <!-- Site Metas -->
  <meta name="keywords" content="" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link rel="shortcut icon" href="{{asset('images/fevicon.png')}}" type="">

  <title> Drop Truck </title>


  <!-- bootstrap core css -->
  <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.css')}}" />

  <!-- fonts style -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">

  <!--owl slider stylesheet -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />

  <!-- font awesome style -->
  <!-- <link href="css/font-awesome.min.css" rel="stylesheet" /> -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Custom styles for this template -->
  <link href="{{asset('css/style.css')}}" rel="stylesheet" />
  <!-- responsive style -->
  <link href="{{asset('css/responsive.css')}}" rel="stylesheet" />

</head>
<style>
  .contact-info {
    margin-bottom: 20px;
  }

  .contact-info i {
    font-size: 20px;
  }

  .contact-info a {
    text-decoration: none;
    color: inherit;
  }

  .contact-info span {
    vertical-align: middle;
  }

  .contact-info .pl-3 {
    margin-left: 10px;
  }

  .contact-info .text-dark {
    display: block;
    margin-left: 13px;
    margin-top: 5px;
  }


  :focus {
    outline: none;
  }

  .col-3 {
    float: left;
    width: 27.33%;
    margin: 40px 3%;
    position: relative;
  }

  input[type="text"] {
    font: 15px/24px 'Muli', sans-serif;
    color: #333;
    width: 100%;
    box-sizing: border-box;
    letter-spacing: 1px;
  }

  .effect-1,
  .effect-2,
  .effect-3,
  .effect-4 {
    border: 0;
    padding: 10px 10px;
    border-radius: 10px;
    border-bottom: 1px solid gray;
    border-width: 3px;
  }

  .effect-1:focus,
  .effect-2:focus,
  .effect-3:focus,
  .effect-4:focus {
    border-radius: 10px;
    border-bottom: 1px solid purple;
    border-width: 3px;
  }

  /* .effect-1 ~ .focus-border,
        .effect-2 ~ .focus-border,
        .effect-3 ~ .focus-border {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            border-radius: 10px;
            width: 0;
            height: 2px;
            background-color: red;
            transition: 0.4s;
        }
        .effect-1:focus ~ .focus-border {
            width: 80%;
            transition: 0.4s;
        }
        .effect-2:focus ~ .focus-border {
            width: 100%;
            transition: 0.4s;
            left: 0;
        }
        .effect-3:focus ~ .focus-border {
            width: 100%;
            transition: 0.4s;
        }
        .effect-4:focus ~ .focus-border {
            width: 100%;
            transition: 0.4s;
        } */
</style>

<body class="sub_page">

  <div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
      <!-- <div class="header_top">
        <div class="container-fluid ">
          <div class="contact_nav">
            <a href="">
              <i class="fa fa-phone" aria-hidden="true"></i>
              <span>
                Call : +01 123455678990
              </span>
            </a>
            <a href="">
              <i class="fa fa-envelope" aria-hidden="true"></i>
              <span>
                Email : demo@gmail.com
              </span>
            </a>
            <a href="">
              <i class="fa fa-map-marker" aria-hidden="true"></i>
              <span>
                Location
              </span>
            </a>
          </div>
        </div>
      </div> -->
      <div class="fixed-top bg-white p-1">
        <div class="container-fluid">
          <nav class="navbar navbar-expand-lg custom_nav-container ">
            <a class="navbar-brand" href="index.html">
              <div class="d-flex">
                <span class="d-flex flex-column logo-p">
                  <img src="{{asset('images/logo.jpeg')}}" alt="Logo" width="60px">
                  <span class="logo-p mt-1 pl-2">Drop Truck <br>On Time Every Time</span>
                </span>
                <!-- <p class="logo-p mt-4">Drop Truck <br>On Time Everey Time</p> -->
              </div>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class=""> </span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav  ">
                <li class="nav-item ">
                  <a class="nav-link" href="{{route('index')}}">Home </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="service.html">Services</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="about.html"> About</a>
                </li>
                <li class="nav-item active">
                  <a class="nav-link" href="contact.html">Contact Us <span class="sr-only">(current)</span> </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> <i class="fa fa-user" aria-hidden="true"></i>+91 7502999899</a>
                </li>
                <a href="https://wa.me/917502999899" target="_blank" class="nav-item  whats-app" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chat with us">
                  <i class="fa-brands fa-whatsapp fa-beat my-float fs-1"></i>
                  <!-- <i class="fa-brands fa-whatsapp my-float fs-1"></i> -->
                </a>
              </ul>
            </div>
          </nav>
        </div>
      </div>
    </header>
    <!-- end header section -->
  </div>

  <!-- contact section -->
  <section class="contact_section pt-5 mt-4" style="margin-top:50px !important;">
    <div class="container">
      <div class="row">
        <div class="col-lg-5 col-md-6 back rounded">
          <h2 class="pb-3 pt-3 fw">
            Contact Us
          </h2>
          <div class="contact-info p-3 pr-5">
            <div class="pb-4">
              <a href="https://www.google.com/maps/place/Drop+Truck+Rental+Services/@13.0131475,80.2208632,17z/data=!3m1!4b1!4m6!3m5!1s0x3a5267000b9fcc3d:0x625e7bc45f11a1e5!8m2!3d13.0131423!4d80.2234381!16s%2Fg%2F11vyrp_wqx?entry=ttu">
                <div class="d-flex">
                  <i class="fa-solid fa-location-dot fa-bounce pt-1"></i>
                  <h4><span class="pl-3 fw-bold">Address:</span></h4>
                </div>
                <span class="text-dark pl">
                  No:
                  7/2, Anjaneyar Koil St, LittleMount, Saidapet, Chennai, Tamil Nadu 600015
                </span>
              </a>
            </div>
            <div class="pb-4">
              <a href="tel:+917502999899">
                <div>
                  <i class="fa fa-phone pb-2 fa-shake pt-1 text-primary" aria-hidden="true"></i>
                  <span class="pl-3 fw-bold">Phone:</span>
                  <h4></h4>
                </div>
                <span class="text-dark pl">
                  Call +91 7502999899
                </span>
              </a>
            </div>
            <div class="pb-4">
              <a href="mailto:demo@gmail.com">
                <i class="fa fa-envelope pb-2" aria-hidden="true"><span class="pl-3">E-Mail:</span></i><br>
                <span class="text-dark pl">
                 admin@droptruck.in
                </span>
              </a>
            </div>
          </div>
        </div>
        <!-- <div class="col-1"></div> -->
        <div class="col-lg col-md-6 pt-3 rounded">
          <h1 class="">Get in Touch</h1>
          <p class="" style="font-size: large;">We will catch you as early as we receive the message</p>
          <div class=" p-3">
            <form action="https://api.web3forms.com/submit" method="POST" class="contact-left" id="contact-form">

              <div class="row g-3">
                <div class="col-md-6">
                  <input type="hidden" name="access_key" value="">

                  <div class="form-floating pb-3">
                    <!-- <input type="text" class="form-control border-0" id="name"  placeholder="Your Name" required> -->
                    <input type="text" class="effect-1" type="text" placeholder="Enter Your Name" name="name">
                    <span class="focus-border"></span>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-floating ">
                    <!-- <input type="text" class="form-control border-0" id="email" placeholder="Your Phone Number" name="Phone"> -->
                    <input type="text" class="effect-2" type="text" placeholder="Your Phone" name="Phone">
                    <span class="focus-border"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating pb-3">
                    <!-- <input type="text" class="form-control border-0" id="subject" name="Address" placeholder="Address"> -->
                    <input type="text" class="effect-3" type="text" placeholder="Enter Your Address" name="Address">
                    <span class="focus-border"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <textarea class="form-control mb-2 effect-4" placeholder="Leave a message here" style="height: 150px" name="message"></textarea>
                  </div>
                </div>
                <div class="col-12">
                  <button class="btn gradient-button w-100 py-3 rounded-pill" type="submit">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="mt-5 px-3">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3887.353482752297!2d80.22086317367231!3d13.013147513968851!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a5267000b9fcc3d%3A0x625e7bc45f11a1e5!2sDrop%20Truck%20Rental%20Services!5e0!3m2!1sen!2sin!4v1719912558499!5m2!1sen!2sin" width="100%" height="350px" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="border rounded "></iframe>
    </div>
  </section>
  <!-- end contact section -->

  <!-- info section -->
  <section class="info_section layout_padding2">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_contact">
            <h4>
              Address
            </h4>
            <div class="contact_link_box">
              <a href="https://www.google.com/maps/place/Drop+Truck+Rental+Services/@13.0131475,80.2208632,17z/data=!3m1!4b1!4m6!3m5!1s0x3a5267000b9fcc3d:0x625e7bc45f11a1e5!8m2!3d13.0131423!4d80.2234381!16s%2Fg%2F11vyrp_wqx?entry=ttu">
                <i class="fa-solid fa-location-dot"></i>
                <span>
                  Drop Truck Rental Services <br>
                  7/2, Anjaneyar Koil St, Little Mount, Saidapet, Chennai, Tamil Nadu 600015
                </span>
              </a>
              <a href="">
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>
                  Call +91 7502999899
                </span>
              </a>
              <a href="">
                <i class="fa fa-envelope" aria-hidden="true"></i>
                <span>
                  demo@gmail.com
                </span>
              </a>
            </div>
          </div>
          <div class="info_social">
            <a href="">
              <i class="fa-brands fa-instagram"></i>
            </a>
            <a href="">
              <i class="fa-brands fa-facebook"></i>
            </a>
            <a href="">
              <i class="fa-brands fa-twitter"></i>
            </a>

          </div>
        </div>
        <div class="col-md-6 col-lg-3 info_col">
          <div class="info_detail">
            <h4>
              Info
            </h4>
            <p>
              necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin
              words, combined with a handful
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-2 mx-auto info_col">
          <div class="info_link_box">
            <h4>
              Links
            </h4>
            <div class="info_links">
              <a class="active" href="index.html">
                <img src="images/nav-bullet.png" alt="">
                Home
              </a>
              <a class="" href="about.html">
                <img src="images/nav-bullet.png" alt="">
                About
              </a>
              <a class="" href="service.html">
                <img src="images/nav-bullet.png" alt="">
                Services
              </a>
              <a class="" href="contact.html">
                <img src="images/nav-bullet.png" alt="">
                Contact Us
              </a>
            </div>
          </div>
        </div>
        <!-- <div class="col-md-6 col-lg-3 info_col ">
          <h4>
            Subscribe
          </h4>
          <form action="#">
            <input type="text" placeholder="Enter email" />
            <button type="submit">
              Subscribe
            </button>
          </form>
        </div> -->
      </div>
    </div>
  </section>

  <!-- end info section -->

  <!-- footer section -->
  <section class="footer_section">
    <div class="container">
      <p>
        &copy; <span id="displayYear"></span> All Rights Reserved By
        <a href="https://karinnovation.in/" class="text-primary">WEHAUL LOGISTICS PRIVATE LIMITED</a>
      </p>
    </div>
  </section>

  <!-- end info section -->
  <!-- jQery -->
  <script type="text/javascript" src="js/jquery-3.4.1.min.js"></script>
  <!-- popper js -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
  </script>
  <!-- bootstrap js -->
  <script type="text/javascript" src="js/bootstrap.js"></script>
  <!-- owl slider -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js">
  </script>
  <!-- custom js -->
  <script type="text/javascript" src="js/custom.js"></script>
  <!-- Google Map -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCh39n5U-4IoWpsVGUHWdqB6puEkhRLdmI&callback=myMap">
  </script>
  <!-- End Google Map -->

</body>

</html>