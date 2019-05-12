<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Fonts --}}
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    {{-- Styles --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @yield('extra-css')
</head>

<body class="font-sans antialiased subpixel-antialiased bg-grey-lighter xs:bg-orange-darker sm:bg-orange-dark md:bg-orange md:text-orange-light lg:bg-orange-lighter xl:bg-orange-lightest">
<div id="app">
    <nav class="navbar flex fixed w-full z-30 top-0 bg-grey-lighter">
        <div class="navbar-brand-wrapper w-1/6 sm:w-16 md:w-16 h-12 text-grey-darker text-center px-4 py-1 border border-white flex justify-between items-center">
            <a class="brand-logo sm:hidden md:hidden text-2xl font-bold tracking-wide no-underline text-grey-dark" href="">bmat-api</a>
            <div class="brand-logo-mini" data-toggle="minimize"><svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-dark" width="24" height="24" viewBox="0 0 24 24"><path d="M14 17H4v2h10v-2zm6-8H4v2h16V9zM4 15h16v-2H4v2zM4 5v2h16V5H4z"/><path d="M0 0h24v24H0z" fill="none"/></svg></div>
        </div>
        <div class="navbar-menu-wrapper w-5/6 sm:w-full md:w-full h-12 text-grey-darker text-center px-4 py-1 border border-white flex justify-end items-center">
            <div class="w-1/3 sm:hidden md:hidden flex justify-start">
                <form method="GET" action="" class="w-3/4">
                    <input class="search-bar rounded-full px-4 w-full text-grey-dark" type="search" name="q" placeholder="Search...">
                </form>
            </div>
            <div class="hor-menu-wrapper w-2/3 sm:w-full md:w-full">
                <ul class="menu list-reset flex justify-end items-center">
                    <li class="flex pl-5 md:pl-3 sm:pl-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-dark" width="24" height="24" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </li>
                    <li class="flex pl-5 md:pl-3 sm:pl-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-dark" width="24" height="24" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                    </li>
                    <li class="flex pl-5 md:pl-3 sm:pl-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-dark" width="24" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/></svg>
                    </li>
                    <li class="dropdown pl-5 md:pl-3 sm:pl-2">
                        <a class="no-underline flex justify-end items-center" href="#">
                            <img class="w-8 h-8 rounded-full mr-2 border-2 border-grey-lightest" src="https://tailwindcss.com/img/jonathan.jpg" alt="Avatar of Jonathan Reinink">
                            <p class="mr-2 text-base text-grey-darker leading-none whitespace-no-wrap sm:hidden">Jonathan Reinink</p>
                            <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-dark w-5 h-5" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/></svg>
                            <ul class="sub-menu list-reset bg-grey-lighter">
                                <li class="py-3 pl-5 md:pl-3 sm:pl-2">
                                    <a class="no-underline text-base text-grey-darker flex" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-darker w-5 h-5" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M13 3h-2v10h2V3zm4.83 2.17l-1.42 1.42C17.99 7.86 19 9.81 19 12c0 3.87-3.13 7-7 7s-7-3.13-7-7c0-2.19 1.01-4.14 2.58-5.42L6.17 5.17C4.23 6.82 3 9.26 3 12c0 4.97 4.03 9 9 9s9-4.03 9-9c0-2.74-1.23-5.18-3.17-6.83z"/></svg>
                                        <p class="pl-2">Sign Out<p>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-xl pt-12 w-full flex flex-wrap mx-auto">
        <nav class="sidebar stylish-scroll-bar sidebar-offcanvas bg-grey-lighter w-1/6 sm:w-16 md:w-16 p-4">
            <ul class="menu list-reset">
                <li class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-grey-darker w-6 h-6" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M12 5.69l5 4.5V18h-2v-6H9v6H7v-7.81l5-4.5M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3z"/></svg>
                    <a class="no-underline text-grey-darker pl-2 pt-3px sm:hidden md:hidden" href="">Dashboard</a>
                </li>
                <li class=""><a class="no-underline" href="">About</a></li>
                <li class=""><a class="no-underline" href="">Portfolio</a></li>
                <li class="dropdown">
                    <a class="no-underline" href="">Services</a>
                    <ul class="sub-menu list-reset">
                        <li class=""><a class="no-underline" href="">Web App Dev</a></li>
                        <li class="dropdown">
                            <a class="no-underline" href="">Mobile App Dev</a>
                            <ul class="sub-menu list-reset">
                                <li class=""><a class="no-underline" href="">iOS</a></li>
                                <li class=""><a class="no-underline" href="">Android</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class=""><a class="no-underline" href="">Contact</a></li>
                <li class="dropdown">
                    <a class="no-underline" href="">Mobile App Dev</a>
                    <ul class="sub-menu list-reset">
                        <li class=""><a class="no-underline" href="">iOS</a></li>
                        <li class=""><a class="no-underline" href="">Android</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div class="main-panel w-5/6 sm:flex-1 md:flex-1 p-5">
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta nesciunt perferendis eos, aliquam voluptas, earum optio! Vero ratione perspiciatis optio explicabo similique a est consequatur, vitae recusandae quisquam iste porro rem maxime, laudantium nostrum eos fuga autem dolorum exercitationem aliquid quo corrupti voluptates. Itaque, laudantium officiis sequi, hic natus nam ullam, pariatur voluptatem aut blanditiis inventore, eveniet numquam earum doloremque omnis obcaecati atque fugit similique sunt eos ab. Cumque pariatur magni facilis laboriosam repellat ipsum delectus totam sit, est amet iusto earum nulla minus ducimus consectetur! Aliquid, voluptate eum quis aut natus soluta quia, laborum tenetur, vero ratione saepe? Ducimus laboriosam omnis, molestiae consequatur sequi aliquid unde necessitatibus consectetur similique? Eos laboriosam nisi distinctio, praesentium nemo, accusantium odit perspiciatis beatae ipsum eligendi quia nesciunt illum voluptates nobis fugit accusamus quibusdam cum! Fuga, eius deserunt maxime necessitatibus, repudiandae cumque quibusdam, qui sequi magnam iste neque adipisci quia corporis. Eos odio itaque corporis totam placeat illum vel ad exercitationem. Quod porro ipsum omnis iste rerum veritatis itaque quos harum ratione culpa nulla, aut animi debitis expedita delectus, voluptas quis, dolore facere voluptatem repudiandae, repellendus iure minus. Suscipit illum impedit nisi enim doloremque molestias quod, necessitatibus culpa ut, quam optio quas modi voluptatum praesentium adipisci repellendus eveniet. Velit impedit nesciunt culpa voluptates, nam corporis, aut animi necessitatibus rem explicabo in aperiam accusantium dignissimos? Molestiae mollitia voluptatum facere, consequuntur soluta, officia quae id commodi ullam, vero illum! Ea tempora, necessitatibus repellendus! Fugiat facere odit laborum officia, quo possimus aspernatur similique ipsum natus voluptatem tempore nemo neque accusantium aliquam ea dolores alias voluptate recusandae adipisci dolorum nam.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta nesciunt perferendis eos, aliquam voluptas, earum optio! Vero ratione perspiciatis optio explicabo similique a est consequatur, vitae recusandae quisquam iste porro rem maxime, laudantium nostrum eos fuga autem dolorum exercitationem aliquid quo corrupti voluptates. Itaque, laudantium officiis sequi, hic natus nam ullam, pariatur voluptatem aut blanditiis inventore, eveniet numquam earum doloremque omnis obcaecati atque fugit similique sunt eos ab. Cumque pariatur magni facilis laboriosam repellat ipsum delectus totam sit, est amet iusto earum nulla minus ducimus consectetur! Aliquid, voluptate eum quis aut natus soluta quia, laborum tenetur, vero ratione saepe? Ducimus laboriosam omnis, molestiae consequatur sequi aliquid unde necessitatibus consectetur similique? Eos laboriosam nisi distinctio, praesentium nemo, accusantium odit perspiciatis beatae ipsum eligendi quia nesciunt illum voluptates nobis fugit accusamus quibusdam cum! Fuga, eius deserunt maxime necessitatibus, repudiandae cumque quibusdam, qui sequi magnam iste neque adipisci quia corporis. Eos odio itaque corporis totam placeat illum vel ad exercitationem. Quod porro ipsum omnis iste rerum veritatis itaque quos harum ratione culpa nulla, aut animi debitis expedita delectus, voluptas quis, dolore facere voluptatem repudiandae, repellendus iure minus. Suscipit illum impedit nisi enim doloremque molestias quod, necessitatibus culpa ut, quam optio quas modi voluptatum praesentium adipisci repellendus eveniet. Velit impedit nesciunt culpa voluptates, nam corporis, aut animi necessitatibus rem explicabo in aperiam accusantium dignissimos? Molestiae mollitia voluptatum facere, consequuntur soluta, officia quae id commodi ullam, vero illum! Ea tempora, necessitatibus repellendus! Fugiat facere odit laborum officia, quo possimus aspernatur similique ipsum natus voluptatem tempore nemo neque accusantium aliquam ea dolores alias voluptate recusandae adipisci dolorum nam.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta nesciunt perferendis eos, aliquam voluptas, earum optio! Vero ratione perspiciatis optio explicabo similique a est consequatur, vitae recusandae quisquam iste porro rem maxime, laudantium nostrum eos fuga autem dolorum exercitationem aliquid quo corrupti voluptates. Itaque, laudantium officiis sequi, hic natus nam ullam, pariatur voluptatem aut blanditiis inventore, eveniet numquam earum doloremque omnis obcaecati atque fugit similique sunt eos ab. Cumque pariatur magni facilis laboriosam repellat ipsum delectus totam sit, est amet iusto earum nulla minus ducimus consectetur! Aliquid, voluptate eum quis aut natus soluta quia, laborum tenetur, vero ratione saepe? Ducimus laboriosam omnis, molestiae consequatur sequi aliquid unde necessitatibus consectetur similique? Eos laboriosam nisi distinctio, praesentium nemo, accusantium odit perspiciatis beatae ipsum eligendi quia nesciunt illum voluptates nobis fugit accusamus quibusdam cum! Fuga, eius deserunt maxime necessitatibus, repudiandae cumque quibusdam, qui sequi magnam iste neque adipisci quia corporis. Eos odio itaque corporis totam placeat illum vel ad exercitationem. Quod porro ipsum omnis iste rerum veritatis itaque quos harum ratione culpa nulla, aut animi debitis expedita delectus, voluptas quis, dolore facere voluptatem repudiandae, repellendus iure minus. Suscipit illum impedit nisi enim doloremque molestias quod, necessitatibus culpa ut, quam optio quas modi voluptatum praesentium adipisci repellendus eveniet. Velit impedit nesciunt culpa voluptates, nam corporis, aut animi necessitatibus rem explicabo in aperiam accusantium dignissimos? Molestiae mollitia voluptatum facere, consequuntur soluta, officia quae id commodi ullam, vero illum! Ea tempora, necessitatibus repellendus! Fugiat facere odit laborum officia, quo possimus aspernatur similique ipsum natus voluptatem tempore nemo neque accusantium aliquam ea dolores alias voluptate recusandae adipisci dolorum nam.
            </p>
            <p>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta nesciunt perferendis eos, aliquam voluptas, earum optio! Vero ratione perspiciatis optio explicabo similique a est consequatur, vitae recusandae quisquam iste porro rem maxime, laudantium nostrum eos fuga autem dolorum exercitationem aliquid quo corrupti voluptates. Itaque, laudantium officiis sequi, hic natus nam ullam, pariatur voluptatem aut blanditiis inventore, eveniet numquam earum doloremque omnis obcaecati atque fugit similique sunt eos ab. Cumque pariatur magni facilis laboriosam repellat ipsum delectus totam sit, est amet iusto earum nulla minus ducimus consectetur! Aliquid, voluptate eum quis aut natus soluta quia, laborum tenetur, vero ratione saepe? Ducimus laboriosam omnis, molestiae consequatur sequi aliquid unde necessitatibus consectetur similique? Eos laboriosam nisi distinctio, praesentium nemo, accusantium odit perspiciatis beatae ipsum eligendi quia nesciunt illum voluptates nobis fugit accusamus quibusdam cum! Fuga, eius deserunt maxime necessitatibus, repudiandae cumque quibusdam, qui sequi magnam iste neque adipisci quia corporis. Eos odio itaque corporis totam placeat illum vel ad exercitationem. Quod porro ipsum omnis iste rerum veritatis itaque quos harum ratione culpa nulla, aut animi debitis expedita delectus, voluptas quis, dolore facere voluptatem repudiandae, repellendus iure minus. Suscipit illum impedit nisi enim doloremque molestias quod, necessitatibus culpa ut, quam optio quas modi voluptatum praesentium adipisci repellendus eveniet. Velit impedit nesciunt culpa voluptates, nam corporis, aut animi necessitatibus rem explicabo in aperiam accusantium dignissimos? Molestiae mollitia voluptatum facere, consequuntur soluta, officia quae id commodi ullam, vero illum! Ea tempora, necessitatibus repellendus! Fugiat facere odit laborum officia, quo possimus aspernatur similique ipsum natus voluptatem tempore nemo neque accusantium aliquam ea dolores alias voluptate recusandae adipisci dolorum nam.
            </p>
        </div>
    </div>

</div>

{{-- Scripts --}}
<script src="{{ asset('js/app.js') }}"></script>

<script type="text/javascript">
    "use strict";

    $( document ).ready(function() {
        var body = $('body');

        // http://www.urbanui.com/majestic/template/js/template.js
        $('[data-toggle="minimize"]').on("click", function() {
            body.toggleClass('sidebar-icon-only');
        });

        // http://www.urbanui.com/majestic/template/js/off-canvas.js
        $('[data-toggle="offcanvas"]').on("click", function() {
            $('.sidebar-offcanvas').toggleClass('active')
        });
    });
</script>

@stack('extra-js')

</body>
</html>
