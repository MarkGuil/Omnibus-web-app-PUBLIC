<!DOCTYPE html>
<html class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Omnibus</title>
    <link href="vendor/locomotive-scroll/locomotive-scroll.min.css" rel="stylesheet">
    <link href="output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="vendor/aos/dist/aos.css" rel="stylesheet">
    <link href="vendor/remixIcon/fonts/remixicon.css" rel="stylesheet">
    <link href="vendor/remixIcon/fonts/remixicon.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        .wave-group {
            outline: 1px solid transparent;
            -webkit-backface-visibility: hidden;
            transform: translateZ(0);
            will-change: transform;
            -webkit-perspective: 1000;
        }
    </style>
</head>

<body class="m-0 p-0 overflow-x-hidden">

    <nav class="navbar fixed w-full py-5 px-5 md:px-20 lg:px-40 z-50 transition-all duration-500 3k:flex 3k:justify-center 3k:items-center ">
        <div class="flex 3k:px-5 3k:w-[1320px] 3k:max-w-[1320px]">
            <a class="" href="#">
                <img src="images/logo.png" class="w-28 md:w-32 lg:w-32 3k:w-40" alt="">
            </a>
            <div class="ml-auto text-white">
                <button id="nav-btn" class="text-3xl cursor-pointer lg:hidden w-8 h-8">
                    <div id="hamburger" class="bg-white rounded absolute w-8 h-1 top-10 -mt-0.5 transition-all duration-500
                            before:content-[''] before:bg-white before:rounded before:absolute before:w-8 before:h-1 before:-translate-x-4 
                            before:-translate-y-3 before:transition-all before:duration-500 
                            after:content-[''] after:bg-white after:rounded after:absolute after:w-8 after:h-1 after:-translate-x-4 
                            after:translate-y-3 after:transition-all after:duration-500 ">
                    </div>
                </button>
                <div class="hidden lg:flex">
                    <ul class="flex pt-1 3k:pt-3">
                        <li class="mx-5 relative">
                            <a class="section-links s1 
                            font-extrabold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6
                            active:before:visible active:before:w-6 active:before:text-white
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="#s1">
                                <small>Home</small>
                            </a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6 
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="partners.php">
                                <small>Partners</small></a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="section-links s2
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="#s2">
                                <small>About</small>
                            </a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="section-links s3
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6 
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="#s3">
                                <small>Features</small></a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="section-links s5
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6 
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="#s5">
                                <small>Details</small></a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="section-links s6
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6 
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="#s6">
                                <small>Contact Us</small></a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="
                            font-semibold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6 
                            active:before:visible active:before:w-6
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="customer/registerCustomer.php">
                                <small>Sign up</small></a>
                        </li>
                    </ul>
                    <a href="customer/loginCustomer.php" class="bg-white text-gray-900 px-5 py-1 mt-1 3k:mt-3 rounded-md ml-3 shadow-sm">Login</a>
                </div>

            </div>
            <div id="nav-menu" class="absolute bg-white text-zinc-900 top-20 left-0 text-4xl w-full flex-col justify-center origin-top animate-nav-bounce hidden lg:hidden transition-all duration-500">
                <div class="min-h-screen flex flex-col items-center py-7" aria-label="mobile">
                    <a href="#s1" class="w-full text-center py-5 hover:opacity-75">Home</a>
                    <a href="partners.php" class="w-full text-center py-5 hover:opacity-75">Partners</a>
                    <a href="#s2" class="w-full text-center py-5 hover:opacity-75">About</a>
                    <a href="#s3" class="w-full text-center py-5 hover:opacity-75">Features</a>
                    <a href="#s5" class="w-full text-center py-5 hover:opacity-75">Details</a>
                    <a href="#s6" class="w-full text-center py-5 hover:opacity-75">Contact Us</a>
                    <a href="customer/registerCustomer.php" class="w-full text-center py-5 hover:opacity-75">Sign Up</a>
                    <a href="customer/loginCustomer.php" class="w-full text-center py-5 hover:opacity-75">Login</a>
                </div>
            </div>
        </div>

    </nav>
    <section id="s1" class="sect s1 relative h-fit lg:h-screen 3k:h-fit bg-cover bg-home-small md:bg-home-large text-white pb-24 md:pb-0 lg:pb-0 lg:flex justify-center items-center">

        <div class="grid grid-cols-7 gap-8 h-full py-5 3k:py-60 4k:py-40 px-4 md:px-40 lg:px-40 3k:px-5 pt-24 lg:pt-0 pb-0 md:pb-24 lg:pb-0 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="col-start-1 col-span-7 lg:col-span-4 flex justify-center order-last lg:order-first lg:justify-start items-center">
                <div class="animate-zoom-in w-10/12 md:w-11/12 lg:w-4/5 text-slate-300 text-center lg:text-left">
                    <h1 class="text-3xl md:text-4xl lg:text-6xl font-black ">Hi there! Welcome to <span class="border-b-4 border-emerald-400 text-white">Omnibus</span></h1>
                    <h2 class="text-lg lg:text-2xl 3k:text-3xl font-normal mt-5 md:mt-9 lg:mt-9">A bus seat booking platform for P2P buses</h2>
                    <div class="text-lg-start mt-14 mb-10 md:inline-flex">
                        <div class="md:mr-2">
                            <a href="apps/Omnibus.zip" download="filename" class="relative btn-get-started scrollto pb-2 px-12 pt-7 bg-emerald-400 rounded-full ">
                                <span class="absolute -translate-x-1 -translate-y-5 text-sm 3k:text-xl text-zinc-200">For Passenger</span> <span class="text-white text-lg 3k:text-2xl">Download</span>
                            </a>
                        </div>
                        <div class="mt-10 md:mt-0">
                            <a href="apps/Omnibus_conductor.zip" download="filename" class="relative btn-get-started scrollto pb-2 px-12 pt-7 bg-emerald-400 rounded-full">
                                <span class="absolute -translate-x-1 -translate-y-5 text-sm 3k:text-xl text-zinc-200">For Conductor</span> <span class="text-white text-lg 3k:text-2xl">Download</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-start-1 col-span-7 lg:col-span-3 flex justify-center lg:justify-end items-center">
                <div class="animate-zoom-in">
                    <img src="images/Artboard 21.png" class="animate-slow-bounce w-52 md:w-72 lg:w-96 " alt="">
                </div>
            </div>

            <canvas id="canvas" class="animate-zoom-in  absolute w-40 h-40 bottom-0 left-0 transition-all"></canvas>

            <svg class="wave-group absolute block bottom-0 left-0 w-full h-16 z-10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
                <defs>
                    <path id="wave-path" class="" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" shape-rendering="auto">
                </defs>
                <g class="animate-wave1">
                    <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)" shape-rendering="optimizeQuality">
                </g>
                <g class="animate-wave2">
                    <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)" shape-rendering="optimizeQuality">
                </g>
                <g class="animate-wave3">
                    <use xlink:href="#wave-path" x="50" y="9" fill="#fff" shape-rendering="optimizeQuality">
                </g>
            </svg>
        </div>
    </section>

    <section id="s2" class="sect s2 relative h-fit text-center sm:text-left bg-white lg:flex justify-center items-center">
        <div class="grid grid-cols-7 gap-3 lg:gap-8 h-full top-10 py-3 md:py-8 lg:py-0 lg:pt-12 px-4 md:px:40 lg:px-20 3k:px-5 3k:w-[1320px] 3k:max-w-[1320px]">
            <div class="col-start-1 col-span-7 lg:col-span-3 flex justify-center lg:justify-start 3k:justify-start items-center " data-aos="fade-right">
                <img src="images/phone bus.png" class="w-10/12 md:w-6/12 lg:w-full" alt="">
            </div>

            <div class="col-start-1 col-span-7 lg:col-span-4 flex justify-start lg:justify-center items-center text-center md:text-start lg:text-start py-4 lg:py-10 px-2 md:px-10" data-aos="fade-left">
                <div class="pb-24 lg:pb-8 3k:pb-3 3k:text-xl">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">For he who is in the pursuit of pleasures should avoid something</h3>
                    <p class="text-zinc-600">To be a pleasure or an exercise. We are here to reject the accusation. Not the very least, but the times of praise. They are freed from pleasure, but they will suffer bodily pains. They are free to be laborious and there is no obligation to do so. He accepts either with pleasure.</p>

                    <div class="mt-10" data-aos="zoom-in" data-aos-delay="50">
                        <div class="float-none md:float-left lg:float-left w-full md:w-16 lg:w-16 h-16 text flex md:block lg:block justify-center items-center">
                            <div class="flex justify-center items-center w-16 h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                <i class="bx bx-rocket text-3xl"></i>
                            </div>
                        </div>
                        <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Fast Booking</a></h4>
                        <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6 text-zinc-600">The pleasures of the spoiled and corrupted do not foresee the pains and troubles they are about to experience, blinded by lust</p>
                    </div>

                    <div class="mt-10" data-aos="zoom-in" data-aos-delay="100">
                        <div class="float-none md:float-left lg:float-left w-full md:w-16 lg:w-16 h-16 text flex md:block lg:block justify-center items-center">
                            <div class="flex justify-center items-center w-16 md:w-full lg:w-full h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                <i class="bx bx-lock text-3xl"></i>
                            </div>
                        </div>
                        <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Secure Payment</a></h4>
                        <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6 text-zinc-600">But in truth we both accuse those who are worthy of just hatred, who are softened by the flattery of present pleasures</p>
                    </div>

                    <div class="mt-10" data-aos="zoom-in" data-aos-delay="150">
                        <div class="float-none md:float-left lg:float-left w-full md:w-16 lg:w-16 h-16 text flex md:block lg:block justify-center items-center">
                            <div class="flex justify-center items-center w-16 md:w-full lg:w-full h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                <i class="bx bx-envelope text-3xl"></i>
                            </div>
                        </div>
                        <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Great Support</a></h4>
                        <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6 text-zinc-600">I will explain that the harsher pleasures have a great effect. And he hates the truth. They either leave less or choose all</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="s3" class="sect s3 h-fit 3k:flex justify-center items-center">
        <div class="px-4 md:px-20 lg:px-40 3k:px-5 md:pb-14 lg:pb-20 pt-0 lg:pt-16 relative h-full 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="pb-10" data-aos="fade-up">
                <h2 class="font-semibold text-sm leading-none mb-1 tracking-widest uppercase text-neutral-400
                after:content-[''] after:w-32 after:h-px after:bg-emerald-400 after:inline-block after:mt-1 after:mr-2">
                    Features
                </h2>
                <p class="m-0 text-4xl font-bold uppercase text-dark-blue">Check The Features</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 font-bold text-dark-blue 3k:text-xl" data-aos="fade-left">
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="50">
                        <i class="ri-bus-2-line text-3xl pr-3 leading-none font-medium" style="color: #ffbb2c;"></i>
                        <h3><a href="">Choose your bus</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4 md:mt-0">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="100">
                        <i class="ri-bar-chart-box-line text-3xl pr-3 leading-none font-medium" style="color: #5578ff;"></i>
                        <h3><a href="">Pain System</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4 md:mt-0">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="150">
                        <i class="ri-calendar-todo-line text-3xl pr-3 leading-none font-medium" style="color: #e80368;"></i>
                        <h3><a href="">But let us see</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="200">
                        <i class="ri-bookmark-line text-3xl pr-3 leading-none font-medium" style="color: #e361ff;"></i>
                        <h3><a href="">Great Pains</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="250">
                        <i class="ri-building-line text-3xl pr-3 leading-none font-medium" style="color: #47aeff;"></i>
                        <h3><a href="">No one</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="300">
                        <i class="ri-coupon-3-line text-3xl pr-3 leading-none font-medium" style="color: #ffa76e;"></i>
                        <h3><a href="">Smooth booking</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="350">
                        <i class="ri-file-list-3-line text-3xl pr-3 leading-none font-medium" style="color: #11dbcf;"></i>
                        <h3><a href="">Midela Teren</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="400">
                        <i class="ri-price-tag-2-line text-3xl pr-3 leading-none font-medium" style="color: #4233ff;"></i>
                        <h3><a href="">Pira Neve</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="450">
                        <i class="ri-anchor-line text-3xl pr-3 leading-none font-medium" style="color: #b2904f;"></i>
                        <h3><a href="">Dirada Pack</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="500">
                        <i class="ri-disc-line text-3xl pr-3 leading-none font-medium" style="color: #b20969;"></i>
                        <h3><a href="">Moton Ideal</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="550">
                        <i class="ri-base-station-line text-3xl pr-3 leading-none font-medium" style="color: #ff5828;"></i>
                        <h3><a href="">Green Park</a></h3>
                    </div>
                </div>
                <div class="col-auto lg:mt-4">
                    <div class="icon-box p-5 flex items-center transition duration-75 ease-in-out" data-aos="zoom-in" data-aos-delay="600">
                        <i class="ri-fingerprint-line text-3xl pr-3 leading-none font-medium" style="color: #29cc61;"></i>
                        <h3><a href="">Leveling the flavor</a></h3>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="h-fit 3k:flex justify-center items-center">
        <div class="px-4 md:px-20 lg:px-40 3k:px-5 md:pb-14 lg:pb-0 relative h-full 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="grid lg:grid grid-cols-2 lg:grid-cols-4 gap-4" data-aos="fade-up">

                <div class="col-auto">
                    <div class="count-box relative pt-8 pb-6 px-8 h-full text-center">
                        <i class="bi bi-emoji-smile text-lg 3k:text-xl bg-emerald-400 p-3 inline-flex items-center justify-center w-12 h-12 rounded-full text-white"></i>
                        <span data-purecounter-start="0" data-purecounter-end="1252" data-purecounter-duration="1" class="purecounter block font-semibold text-3xl text-dark-blue my-2"></span>
                        <p class="p-0 m-0 text-xs 3k:text-base font-medium">Users</p>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="count-box relative pt-8 pb-6 px-8 h-full text-center">
                        <i class="bi bi-journal-richtext text-lg 3k:text-xl bg-emerald-400 p-3 inline-flex items-center justify-center w-12 h-12 rounded-full text-white"></i>
                        <span data-purecounter-start="0" data-purecounter-end="10" data-purecounter-duration="1" class="purecounter block font-semibold text-3xl text-dark-blue my-2"></span>
                        <p class="p-0 m-0 text-xs 3k:text-base font-medium">Partners</p>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="count-box relative pt-8 pb-6 px-8 h-full text-center">
                        <i class="bi bi-headset text-lg 3k:text-xl bg-emerald-400 p-3 inline-flex items-center justify-center w-12 h-12 rounded-full text-white"></i>
                        <span data-purecounter-start="0" data-purecounter-end="48" data-purecounter-duration="1" class="purecounter block font-semibold text-3xl text-dark-blue my-2"></span>
                        <p class="p-0 m-0 text-xs 3k:text-base font-medium">Hours Of Support</p>
                    </div>
                </div>

                <div class="col-auto">
                    <div class="count-box relative pt-8 pb-6 px-8 h-full text-center">
                        <i class="bi bi-people text-lg 3k:text-xl bg-emerald-400 p-3 inline-flex items-center justify-center w-12 h-12 rounded-full text-white"></i>
                        <span data-purecounter-start="0" data-purecounter-end="5" data-purecounter-duration="1" class="purecounter block font-semibold text-3xl text-dark-blue my-2"></span>
                        <p class="p-0 m-0 text-xs 3k:text-base font-medium">Hard Workers</p>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <section id="s5" class="sect s5 h-fit 3k:flex justify-center items-center">
        <div class="px-4 md:px-20 lg:px-40 3k:px-5 md:pb-14 lg:pb-20 pt-0 lg:pt-20 relative h-full 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="grid grid-cols-12 gap-3 lg:gap-8">
                <div class="col-start-1 col-span-12 md:col-span-4 flex justify-center lg:justify-end items-center px-14 md:px-44 lg:px-3.5 3xl:px-24 3k:px-3" data-aos="fade-right">
                    <img src="images/details-1.png" class="" alt="">
                </div>
                <div class="col-start-1 col-span-12 md:col-span-8 pt-4 text-zinc-600 3k:text-lg" data-aos="fade-up">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">Voluptatem dignissimos provident quasi corporis voluptates sit assumenda.</h3>
                    <p class="pb-5 italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua.
                    </p>
                    <ul>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Iure at voluptas aspernatur dignissimos doloribus repudiandae.</li>
                        <li class="pb-5"><i class="bi bi-check text-emerald-400"></i> Est ipsa assumenda id facilis nesciunt placeat sed doloribus praesentium.</li>
                    </ul>
                    <p>
                        Voluptas nisi in quia excepturi nihil voluptas nam et ut. Expedita omnis eum consequatur non. Sed in asperiores aut repellendus. Error quisquam ab maiores. Quibusdam sit in officia
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-3 lg:gap-8 mt-20">
                <div class="col-start-1 col-span-12 md:col-span-4 order-1 md:order-2 px-14 md:px-44 lg:px-3 3xl:px-20 3k:px-3" data-aos="fade-left">
                    <img src="images/details-2.png" class="img-fluid" alt="">
                </div>
                <div class="col-start-1 col-span-12 md:col-span-8 pt-5 order-2 md:order-1 text-zinc-600 3k:text-lg" data-aos="fade-up">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">Corporis temporibus maiores provident</h3>
                    <p class="pb-5 italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua.
                    </p>
                    <p class="pb-5">
                        Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum
                    </p>
                    <p class="pb-5">
                        Inventore id enim dolor dicta qui et magni molestiae. Mollitia optio officia illum ut cupiditate eos autem. Soluta dolorum repellendus repellat amet autem rerum illum in. Quibusdam occaecati est nisi esse. Saepe aut dignissimos distinctio id enim.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-3 lg:gap-8 mt-20">
                <div class="col-start-1 col-span-12 md:col-span-4 px-20 md:px-44 lg:px-14 3xl:px-32 3k:px-20" data-aos="fade-right">
                    <img src="images/details-3.png" class="img-fluid" alt="">
                </div>
                <div class="col-start-1 col-span-12 md:col-span-8 pt-5 text-zinc-600 3k:text-lg" data-aos="fade-up">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">Sunt consequatur ad ut est nulla consectetur reiciendis animi voluptas</h3>
                    <p class="pb-5 italic">Cupiditate placeat cupiditate placeat est ipsam culpa. Delectus quia minima quod. Sunt saepe odit aut quia voluptatem hic voluptas dolor doloremque.</p>
                    <ul>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
                        <li class="pb-5"><i class="bi bi-check text-emerald-400"></i> Facilis ut et voluptatem aperiam. Autem soluta ad fugiat.</li>
                    </ul>
                    <p>
                        Qui consequatur temporibus. Enim et corporis sit sunt harum praesentium suscipit ut voluptatem. Et nihil magni debitis consequatur est.
                    </p>
                    <p>
                        Suscipit enim et. Ut optio esse quidem quam reiciendis esse odit excepturi. Vel dolores rerum soluta explicabo vel fugiat eum non.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-3 lg:gap-8 mt-20">
                <div class="col-start-1 col-span-12 md:col-span-4 order-1 md:order-2 px-14 md:px-44 lg:px-5 3xl:px-24 3k:px-3" data-aos="fade-left">
                    <img src="images/details-4.png" class="img-fluid " alt="">
                </div>
                <div class="col-start-1 col-span-12 md:col-span-8 pt-5 order-2 md:order-1 text-zinc-600 3k:text-lg" data-aos="fade-up">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">Quas et necessitatibus eaque impedit ipsum animi consequatur incidunt in</h3>
                    <p class="pb-5 italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua.
                    </p>
                    <p class="pb-5">
                        Ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate
                        velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                        culpa qui officia deserunt mollit anim id est laborum
                    </p>
                    <ul>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Et praesentium laboriosam architecto nam .</li>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Eius et voluptate. Enim earum tempore aliquid. Nobis et sunt consequatur. Aut repellat in numquam velit quo dignissimos et.</li>
                        <li class="pb-4"><i class="bi bi-check text-emerald-400"></i> Facilis ut et voluptatem aperiam. Autem soluta ad fugiat.</li>
                    </ul>
                </div>
            </div>

        </div>
    </section>

    <section id="s6" class="sect s6 h-fit 3k:flex justify-center items-center">
        <div class="px-4 md:px-20 lg:px-40 3k:px-5 pb-10 md:pb-14 lg:pb-20 relative h-full 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="pb-10" data-aos="fade-up">
                <h2 class="font-semibold text-sm leading-none mb-1 tracking-widest uppercase text-neutral-400
                after:content-[''] after:w-32 after:h-px after:bg-emerald-400 after:inline-block after:mt-1 after:mr-2">
                    Contact
                </h2>
                <p class="m-0 text-4xl font-bold uppercase text-dark-blue">Contact Us</p>
            </div>

            <div class="lg:grid grid-cols-12 gap-4">

                <div class="col-start-1 col-span-4" data-aos="fade-right" data-aos-delay="100">
                    <div class="font-bold text-dark-blue 3k:text-xl">

                        <div class="">
                            <div class="float-left w-16 h-16 text">
                                <div class="flex justify-center items-center w-16 h-full rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                    <i class="bi bi-geo-alt text-2xl"></i>
                                </div>
                            </div>
                            <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-2xl 3k:text-3xl"><a href="" class=transition duration-75 ease-in-out hover:text-emerald-300">Location:</a></h4>
                            <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6">Urdaneta City, Pangasinan</p>
                        </div>

                        <div class="mt-10">
                            <div class="float-left w-16 h-16 text">
                                <div class="flex justify-center items-center w-16 h-full rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                    <i class="bi bi-envelope text-2xl"></i>
                                </div>
                            </div>
                            <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-2xl 3k:text-3xl"><a href="" class=transition duration-75 ease-in-out hover:text-emerald-300">Email:</a></h4>
                            <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6">support.omnibus@gmail.com</p>
                        </div>

                        <div class="mt-10">
                            <div class="float-left w-16 h-16 text">
                                <div class="flex justify-center items-center w-16 h-full rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                                    <i class="bi bi-phone text-2xl"></i>
                                </div>
                            </div>
                            <h4 class="ml-0 md:ml-20 lg:ml-20 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-2xl 3k:text-3xl"><a href="" class=transition duration-75 ease-in-out hover:text-emerald-300">Call:</a></h4>
                            <p class="px-10 md:px-20 lg:px-0 ml-0 lg:ml-20 text-sm 3k:text-lg leading-6">+63 XXX XXX XXXX</p>
                        </div>

                    </div>

                </div>

                <div class="col-start-5 col-span-12 mt-7 lg:mt-0" data-aos="fade-left" data-aos-delay="200">

                    <form action="forms/contact.php" method="post" role="form" class="php-email-form text-sm">
                        <div class="grid grid-cols-2">
                            <div class="col-auto form-group w-full pb-3 px-2">
                                <input type="text" name="name" class="h-11 w-full py-1 px-3 border border-zinc-300 text-black" id="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-auto form-group w-full pb-3 px-2">
                                <input type="email" class="h-11 w-full py-1 px-3 border border-zinc-300" name="email" id="email" placeholder="Your Email" required>
                            </div>
                        </div>
                        <div class="form-group w-full pb-3 px-2 mt-3">
                            <input type="text" class="form-control h-11 w-full py-1 px-3 border border-zinc-300" name="subject" id="subject" placeholder="Subject" required>
                        </div>
                        <div class="form-group w-full pb-2 px-2 mt-3">
                            <textarea class="form-control w-full py-1 px-3 border border-zinc-300" name="message" rows="5" placeholder="Message" required></textarea>
                        </div>
                        <div class=" invisible">
                            <div class="loading">Loading</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Your message has been sent. Thank you!</div>
                        </div>
                        <div class="text-center"><button type="submit" class="btn-get-started scrollto py-2 md:py-3 lg:py-3 px-7 bg-emerald-400 rounded-full text-white 3k:text-2xl">Send Message</button></div>
                    </form>

                </div>

            </div>

        </div>
    </section>


    <div class="footer-observer w-full py-5"></div>
    <footer class="page-footer w-full py-5 px-5 md:px-20 lg:px-40 z-50 transition-all duration-500 3k:flex 3k:justify-center 3k:items-center bg-dark-blue text-zinc-300 bottom-0">
        <div class=" md:flex 3k:px-5 3k:w-[1320px] 3k:max-w-[1320px] text-center md:text-start">
            <div class="text-light py-3 bg-primary 3k:text-lg">
                © 2021 Copyright
                <a class="text-light font-bold" href="">Omnibus</a>
                . All Rights Reserved
            </div>
            <div class="ml-auto">
                <div class="social-links mt-3 text-lg">
                    <a href="#" class="twitter"><i class="bx bxl-twitter px-2 py-2 transition-all rounded-full duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white"></i></a>
                    <a href="#" class="facebook"><i class="bx bxl-facebook px-2 py-2 transition-all rounded-full duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white"></i></a>
                    <a href="#" class="instagram"><i class="bx bxl-instagram px-2 py-2 transition-all rounded-full duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white"></i></a>
                    <a href="#" class="google-plus"><i class="bx bxl-google-plus px-2 py-2 transition-all rounded-full duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white"></i></a>
                    <a href="#" class="linkedin"><i class="bx bxl-linkedin px-2 py-2 transition-all rounded-full duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white"></i></a>
                </div>
            </div>
        </div>

    </footer>


    <script defer src="app.js"></script>
    <script defer src="nav.js"></script>
    <script src="animation.js"></script>
    <script src="vendor/aos/dist/aos.js"></script>
    <script src="vendor/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="vendor/lenis/lenis.js"></script>
    <!-- <script src="vendor/locomotive-scroll/locomotive-scroll.min.js"></script> -->

    <script>
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // https://www.desmos.com/calculator/brs54l4xou
            direction: 'vertical', // vertical, horizontal
            gestureDirection: 'vertical', // vertical, horizontal, both
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        })

        //get scroll value
        lenis.on('scroll', ({
            scroll,
            limit,
            velocity,
            direction,
            progress
        }) => {
            console.log({
                scroll,
                limit,
                velocity,
                direction,
                progress
            })
        })

        function raf(time) {
            lenis.raf(time)
            requestAnimationFrame(raf)
        }

        requestAnimationFrame(raf)

        AOS.init();
        new PureCounter();
    </script>
</body>

</html>