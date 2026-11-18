@extends('layouts.app')

@section('navbar')

    <a class="btn btn-gc-outline rounded-pill px-3 px-md-4 py-2 fw-semibold"
        href="{{ route('register') }}">
        Register
    </a>

    <a class="btn btn-gc-primary rounded-pill px-3 px-md-4 py-2 fw-semibold"
        href="{{ route('login') }}">
        Login
    </a>

@endsection

@section('content')

    {{-- Hero banner --}}
    <section class="gc-hero container-fluid px-3 px-md-5 px-xl-5"
                aria-labelledby="hero-heading">

        <div class="row align-items-center justify-content-center g-4 g-lg-5 w-100 mx-auto">

            {{-- Left content --}}
            <div class="col-12 col-lg-6" style="padding-left: 140px">

                <div class="gc-welcome mb-4">
                    Welcome to NoteApp
                </div>

                <h1 class="gc-hero-title" id="hero-heading">
                    <span class="gc-accent" style="font-size: 80px">Write Down</span><br>
                    Yours Ideas
                </h1>

                <p class="gc-description mt-4 mb-4">
                    A simple and convenient space to capture your ideas, create notes, 
                    and keep track of what matters. Organize your thoughts, 
                    manage your notes, and access your information whenever you need it.
                </p>

                <a class="btn btn-gc-primary rounded-pill px-4 py-3 fw-bold"
                    href="{{ route('login') }}">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" width="30px" height="15px" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                    </svg>

                    LOGIN / REGISTER TO BEGIN
                </a>
            </div>

            {{-- Right content: Note illustration --}}
            <div class="col-12 col-lg-6">

                <div class="gc-hero-note-area"
                        role="img"
                        aria-label="Minh họa một ghi chú ý tưởng">

                    <div class="gc-note-stack">

                        <div class="gc-note-back"></div>

                        <div class="gc-note-back gc-note-back--second"></div>

                        <article class="gc-note-card">

                            <div class="d-flex align-items-center justify-content-between gc-note-meta">
                                <span>NEW IDEA</span>
                                <span aria-hidden="true">•••</span>
                            </div>

                            <h2 class="gc-note-title">
                                Everything<br>
                                begins with
                            </h2>

                            <p class="gc-note-copy mb-0">
                                a single thought.
                            </p>

                            <div class="gc-note-divider"></div>

                            <div class="gc-note-footer d-flex align-items-center gap-2">
                                <span aria-hidden="true">✓</span>
                                <span>Write down now!</span>
                            </div>

                            <div class="gc-note-tag mt-2">
                                Inspiration
                            </div>

                        </article>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

</body>
</html>
