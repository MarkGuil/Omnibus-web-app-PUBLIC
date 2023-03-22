<!DOCTYPE html>
<html class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Omnibus</title>
    <link href="output.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link href="vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="vendor/aos/dist/aos.css" rel="stylesheet">
    <link href="vendor/remixIcon/fonts/remixicon.css" rel="stylesheet">
    <link href="vendor/remixIcon/fonts/remixicon.css" rel="stylesheet">
    <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* .wave-group {
            outline: 1px solid transparent;
            -webkit-backface-visibility: hidden;
            transform: translateZ(0);
            will-change: transform;
            -webkit-perspective: 1000;
        } */
    </style>

</head>

<body class="m-0 p-0 overflow-x-hidden">

    <nav class="navbar fixed w-full py-5 px-5 md:px-20 lg:px-40 z-50 transition-all duration-500 3k:flex 3k:justify-center 3k:items-center ">
        <div class="flex 3k:px-5 3k:w-[1320px] 3k:max-w-[1320px]">
            <a class="" href="index.php">
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
                            <a class="
                            font-extrabold text-lg 3k:text-xl text-slate-300 transition-all duration-500 pb-1 
                          hover:text-white hover:before:visible hover:before:w-6
                            active:before:visible active:before:w-6 active:before:text-white
                            before:content-[''] before:absolute before:w-0 before:h-0.5 before:bottom-0.5 before:invisible
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="index.php">
                                <small>Home</small>
                            </a>
                        </li>
                        <li class="mx-5 relative">
                            <a class="section-links s1
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
                          before:bg-emerald-400 before:transition-all before:duration-500 before:ease-in-out" href="validation/registerb.php">
                                <small>Sign up</small></a>
                        </li>
                    </ul>

                    </ul>
                    <a href="validation/loginb.php" class="bg-white text-gray-900 px-5 py-1 mt-1 3k:mt-3 rounded-md ml-3 shadow-sm">Login</a>
                </div>

            </div>
            <div id="nav-menu" class="absolute bg-white text-zinc-900 top-20 left-0 text-4xl w-full flex-col justify-center origin-top animate-nav-bounce hidden lg:hidden transition-all duration-500">
                <div class="min-h-screen flex flex-col items-center py-7" aria-label="mobile">
                    <a href="index.php" class="w-full text-center py-5 hover:opacity-75">Home</a>
                    <a href="partners.php" class="w-full text-center py-5 hover:opacity-75">Partners</a>
                    <a href="#s2" class="w-full text-center py-5 hover:opacity-75">About</a>
                    <a href="#s6" class="w-full text-center py-5 hover:opacity-75">Contact Us</a>
                    <a href="validation/registerb.php" class="w-full text-center py-5 hover:opacity-75">Sign Up</a>
                    <a href="validation/loginb.php" class="w-full text-center py-5 hover:opacity-75">Login</a>
                </div>
            </div>
        </div>

    </nav>

    <section id="s1" class="sect s1 relative h-fit bg-cover bg-home-small md:bg-home-large text-white pb-24 md:pb-0 lg:pb-0 lg:flex justify-center items-center">

        <div class="grid grid-cols-8 gap-8 h-full py-5 3k:py-40 px-4 md:px-28 lg:px-40 3k:px-5 pt-24 lg:pt-0 pb-0 md:pb-24 lg:pb-0 3k:w-[1320px] 3k:max-w-[1320px]">

            <div class="col-start-1 col-span-8 lg:col-span-6 flex justify-center lg:justify-start items-center py-32">
                <div class="animate-zoom-in w-10/12 md:w-11/12 lg:w-4/5 text-slate-300 text-center lg:text-left">
                    <h1 class="text-xl md:text-2xl lg:text-4xl font-black ">Hi there! Welcome to <span class="border-b-4 border-emerald-400 text-white">Omnibus</span></h1>
                    <h2 class="text-lg lg:text-xl 3k:text-3xl font-normal mt-5 md:mt-7 lg:mt-7">By becoming a Omnibus partner you can potentially attract more customer/ passenger and increase your market presence. Joining for partnership is completely free.</h2>
                    <div class="text-lg-start mt-14 mb-10 md:inline-flex">
                        <div class="md:mr-2">
                            <a href="validation/logina.php" class="relative btn-get-started scrollto py-4 px-16 3k:px-20 bg-emerald-400 rounded-full ">
                                <span class="text-white text-lg 3k:text-2xl">Get Started</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>


            <!-- <svg class="wave-group absolute block bottom-0 left-0 w-full h-16 z-10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28 " preserveAspectRatio="none">
                <defs>
                    <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" shape-rendering="auto">
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
            </svg> -->
        </div>
    </section>

    <section id="s2" class="sect s2 relative h-fit text-center sm:text-left bg-white lg:flex justify-center items-center">
        <div class="grid grid-cols-12 gap-3 lg:gap-8 h-full top-10 py-3 md:py-8 lg:py-0 lg:pt-12 px-4 md:px:40 lg:px-40 3k:px-5 3k:w-[1320px] 3k:max-w-[1320px]">
            <div class="col-start-1 col-span-12 flex justify-center items-center" data-aos="fade-right">
                <div class="mt-10" data-aos="zoom-in" data-aos-delay="50">
                    <h3 class="text-3xl font-bold text-dark-blue mb-4">For he who is in the pursuit of pleasures should avoid something</h3>
                    <p class="text-zinc-600">To be a pleasure or an exercise. We are here to reject the accusation. Not the very least, but the times of praise. They are freed from pleasure, but they will suffer bodily pains. They are free to be laborious and there is no obligation to do so. He accepts either with pleasure.</p>
                </div>
            </div>
            <div class="col-start-1 col-span-12 lg:col-span-4" data-aos="fade-right">
                <div class="mt-10 text-center" data-aos="zoom-in" data-aos-delay="50">
                    <div class="float-none w-full h-16 text-center flex justify-center items-center">
                        <div class="flex justify-center items-center w-16 h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                            <i class="bx bx-bar-chart text-3xl"></i>
                        </div>
                    </div>
                    <h4 class="ml-0 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Dashboard</a></h4>
                    <p class="px-10 md:px-20 lg:px-10 ml-0 text-sm 3k:text-lg leading-6 text-zinc-600">The pleasures of the spoiled and corrupted do not foresee the pains and troubles they are about to experience, blinded by lust</p>
                </div>
            </div>

            <div class="col-start-1 col-span-12 lg:col-span-4" data-aos="fade-right">
                <div class="mt-10 text-center" data-aos="zoom-in" data-aos-delay="50">
                    <div class="float-none w-full h-16 text-center flex justify-center items-center">
                        <div class="flex justify-center items-center w-16 h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                            <i class="bx bx-alarm-exclamation text-3xl"></i>

                        </div>
                    </div>
                    <h4 class="ml-0 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Bus Seat Protocol Support</a></h4>
                    <p class="px-10 md:px-20 lg:px-10 ml-0 text-sm 3k:text-lg leading-6 text-zinc-600">The pleasures of the spoiled and corrupted do not foresee the pains and troubles they are about to experience, blinded by lust</p>
                </div>
            </div>

            <div class="col-start-1 col-span-12 lg:col-span-4 min-h-" data-aos="fade-right">
                <div class="mt-10 text-center" data-aos="zoom-in" data-aos-delay="50">
                    <div class="float-none w-full h-16 text-center flex justify-center items-center">
                        <div class="flex justify-center items-center w-16 h-full border-2 border-emerald-400 text-emerald-400 rounded-full transition duration-75 ease-in-out hover:border-emerald-300 hover:bg-emerald-300 hover:text-white">
                            <i class="bx bx-envelope text-3xl"></i>
                        </div>
                    </div>
                    <h4 class="ml-0 mb-2 mt-3 md:mt-0 lg:mt-0 font-bold text-lg 3k:text-xl"><a href="" class="text-zinc-800 transition duration-75 ease-in-out hover:text-emerald-300">Great Suppport</a></h4>
                    <p class="px-10 md:px-20 lg:px-10 ml-0 text-sm 3k:text-lg leading-6 text-zinc-600">The pleasures of the spoiled and corrupted do not foresee the pains and troubles they are about to experience, blinded by lust</p>
                </div>
            </div>

        </div>
    </section>

    <section id="s6" class="sect s6 h-fit 3k:flex justify-center items-center">
        <div class="px-4 md:px-20 lg:px-40 3k:px-5 pb-10 md:pb-14 lg:pb-20 pt-16 lg:pt-20  relative h-full 3k:w-[1320px] 3k:max-w-[1320px]">

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
    <script src="vendor/aos/dist/aos.js"></script>
    <script src="vendor/purecounterjs/dist/purecounter_vanilla.js"></script>
    <script src="vendor/lenis/lenis.js"></script>
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