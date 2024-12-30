@extends('components.front.layouts.front')

@section('content')

    <section class="relative mt-28 lg:py-8">
        <div class="container px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="relative overflow-hidden rounded-lg aspect-w-4 aspect-h-3">
                    <a href="#lightbox-1">
                        <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                            alt="Main Property Image"
                            class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105">
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div class="relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">
                        <a href="#lightbox-2">
                            <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                                alt="Property Image 2"
                                class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105">
                        </a>
                    </div>
                    <div class="relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">
                        <a href="#lightbox-3">
                            <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                alt="Property Image 3"
                                class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105">
                        </a>
                    </div>
                    <div class="relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">
                        <a href="#lightbox-4">
                            <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                                alt="Property Image 4"
                                class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105">
                        </a>
                    </div>
                    <div class="relative overflow-hidden rounded-lg aspect-w-1 aspect-h-1">
                        <a href="#lightbox-5" class="block relative">
                            <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                                alt="Property Image 5"
                                class="w-full h-full object-cover cursor-pointer transition-transform duration-300 hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">+3</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lightbox -->
            <div id="lightbox-1" class="lightbox">
                <a href="#" class="absolute top-4 right-4 text-white text-4xl">&times;</a>
                <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                    alt="Main Property Image">
                <a href="#lightbox-5"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&lt;</a>
                <a href="#lightbox-2"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&gt;</a>
            </div>
            <div id="lightbox-2" class="lightbox">
                <a href="#" class="absolute top-4 right-4 text-white text-4xl">&times;</a>
                <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                    alt="Property Image 2">
                <a href="#lightbox-1"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&lt;</a>
                <a href="#lightbox-3"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&gt;</a>
            </div>
            <div id="lightbox-3" class="lightbox">
                <a href="#" class="absolute top-4 right-4 text-white text-4xl">&times;</a>
                <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                    alt="Property Image 3">
                <a href="#lightbox-2"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&lt;</a>
                <a href="#lightbox-4"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&gt;</a>
            </div>
            <div id="lightbox-4" class="lightbox">
                <a href="#" class="absolute top-4 right-4 text-white text-4xl">&times;</a>
                <img src="https://a0.muscache.com/im/pictures/miso/Hosting-39793877/original/a0d92972-40f3-46af-a507-f93f5b945702.jpeg?im_w=720"
                    alt="Property Image 4">
                <a href="#lightbox-3"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&lt;</a>
                <a href="#lightbox-5"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&gt;</a>
            </div>
            <div id="lightbox-5" class="lightbox">
                <a href="#" class="absolute top-4 right-4 text-white text-4xl">&times;</a>
                <img src="https://a0.muscache.com/im/pictures/4e26e5ec-0c7d-4f6a-8580-f8a00f45081e.jpg?im_w=720"
                    alt="Property Image 5">
                <a href="#lightbox-4"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&lt;</a>
                <a href="#lightbox-1"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white text-3xl">&gt;</a>
            </div>


            <div class="container md:mt-24 mt-16">
                <div class="grid md:grid-cols-12 grid-cols-1 gap-[30px]">
                    <div class="lg:col-span-8 md:col-span-7">
                        <h4 class="text-2xl font-medium">10765 Hillshire Ave, Baton Rouge, LA 70810, USA</h4>

                        <ul class="py-6 flex items-center list-none">
                            <li class="flex items-center lg:me-6 me-4">
                                <i class="uil uil-compress-arrows lg:text-3xl text-2xl me-2 text-orange-600"></i>
                                <span class="lg:text-xl">8000sqf</span>
                            </li>

                            <li class="flex items-center lg:me-6 me-4">
                                <i class="uil uil-bed-double lg:text-3xl text-2xl me-2 text-orange-600"></i>
                                <span class="lg:text-xl">4 Beds</span>
                            </li>

                            <li class="flex items-center">
                                <i class="uil uil-bath lg:text-3xl text-2xl me-2 text-orange-600"></i>
                                <span class="lg:text-xl">4 Baths</span>
                            </li>
                        </ul>

                        <p class="text-slate-400">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore
                            veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem
                            quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui
                            ratione voluptatem sequi nesciunt.</p>
                        <p class="text-slate-400 mt-4">But I must explain to you how all this mistaken idea of
                            denouncing pleasure and praising pain was born and I will give you a complete account of the
                            system, and expound the actual teachings of the great explorer of the truth, the
                            master-builder of human happiness.</p>
                        <p class="text-slate-400 mt-4">Nor again is there anyone who loves or pursues or desires to
                            obtain pain of itself, because it is pain, but because occasionally circumstances occur in
                            which toil and pain can procure him some great pleasure.</p>

                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden mt-5">
                            <div class="p-6">
                                <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-white">Customer Reviews</h2>

                                <!-- Write a review form -->
                                <form id="reviewForm" class="mb-8">
                                    <h3 class="text-xl font-semibold mb-4 text-gray-700 dark:text-gray-300">Write a
                                        review</h3>
                                    <div class="flex items-center mb-4" id="starRating">
                                        <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer"></i>
                                        <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer"></i>
                                        <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer"></i>
                                        <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer"></i>
                                        <i class="ri-star-fill text-2xl text-gray-300 cursor-pointer"></i>
                                    </div>
                                    <textarea id="reviewContent"
                                        class="w-full p-2 border rounded-md mb-4 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-white"
                                        rows="4" placeholder="Enter your review..." required></textarea>
                                    <button type="submit"
                                        class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600 transition-colors duration-300">
                                        Submit Review
                                    </button>
                                </form>

                                <!-- Review summary -->
                                <div class="flex items-center justify-between mb-6">
                                    <span class="text-lg font-semibold text-gray-700 dark:text-gray-300"
                                        id="reviewCount">0 Review(s)</span>
                                    <div class="flex items-center">
                                        <div class="flex mr-2" id="averageRatingStars"></div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                            id="averageRatingText">0 out of 5</span>
                                    </div>
                                </div>

                                <!-- Review list -->
                                <div class="space-y-6" id="reviewList">
                                    <!-- Review items will be dynamically added here -->
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="lg:col-span-4 md:col-span-5">
                        <div class="sticky top-20">
                            <div class="rounded-md bg-slate-50 dark:bg-slate-800 shadow dark:shadow-gray-700">
                                <div class="p-6">
                                    <h5 class="text-2xl font-medium">Price:</h5>

                                    <div class="flex justify-between items-center mt-4">
                                        <span class="text-xl font-medium">$ 45,231</span>

                                        <span
                                            class="bg-orange-600/10 text-orange-600 text-sm px-2.5 py-0.75 rounded h-6">For
                                            Sale</span>
                                    </div>

                                    <ul class="list-none mt-4">
                                        <li class="flex justify-between items-center">
                                            <span class="text-slate-400 text-sm">Days on Hously</span>
                                            <span class="font-medium text-sm">124 Days</span>
                                        </li>

                                        <li class="flex justify-between items-center mt-2">
                                            <span class="text-slate-400 text-sm">Price per sq ft</span>
                                            <span class="font-medium text-sm">$ 186</span>
                                        </li>

                                        <li class="flex justify-between items-center mt-2">
                                            <span class="text-slate-400 text-sm">Monthly Payment (estimate)</span>
                                            <span class="font-medium text-sm">$ 1497/Monthly</span>
                                        </li>
                                    </ul>
                                </div>

                                <div
                                    class="flex space-x-12 lg:col-span-8 lg:justify-center lg:items-center lg:px-6 p-4">
                                    <a href="#"
                                        class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base">
                                        Book Now
                                    </a>
                                    <a href="#"
                                        class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base">
                                        Offer Now
                                    </a>
                                </div>
                            </div>

                            <div class="mt-12 text-center">
                                <h3 class="mb-6 text-xl leading-normal font-medium text-black dark:text-white">Have
                                    Question ? Get in touch!</h3>

                                <div class="mt-6 lg:col-span-8 lg:justify-center lg:items-center lg:px-6 p-4">
                                    <a href="contact.html"
                                        class="btn bg-transparent hover:bg-orange-600 border border-orange-600 text-orange-600 hover:text-white rounded-md px-6 py-2 text-sm lg:text-base"><i
                                            class="uil uil-phone align-middle me-2"></i> Contact us</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Fixed Navbar for Mobile -->
        <div
            class="lg:hidden fixed lg:static top-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-lg p-4 flex justify-between items-center lg:shadow-none lg:p-0 lg:mb-8 lg:gap-[30px] mx-auto">
            <!-- Price and Status -->
            <div class="flex flex-col lg:col-span-4 lg:justify-center lg:px-6">
                <h5 class="text-lg lg:text-2xl font-medium text-gray-800 dark:text-white">Price:</h5>
                <div class="flex items-center mt-1 lg:mt-4">
                    <span class="text-lg lg:text-xl font-semibold text-gray-800 dark:text-white">$45,231</span>

                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-2 lg:col-span-8 lg:justify-end lg:items-center lg:px-6">
                <a href="#"
                    class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base">
                    Book Now
                </a>
                <a href="#"
                    class="btn bg-orange-600 hover:bg-orange-700 text-white rounded-md px-6 py-2 text-sm lg:text-base">
                    Offer Now
                </a>
            </div>
        </div>
    </section>

    @endsection
