@include('includes.head')

<div class="d-flex flex-column flex-lg-row h-lg-full bg-surface-secondary">
    <!-- Vertical Navbar -->
    @include('includes.sidebar')
    <!-- Main content -->
    <div class="h-screen flex-grow-1 overflow-y-lg-auto">
        <!-- Main -->
        <main class="py-6 bg-surface-secondary">
            <div class="container-fluid">
                @yield('content')
            </div>
        </main>
    </div>
</div>

@include('includes.footer')

