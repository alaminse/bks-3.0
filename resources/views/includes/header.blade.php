<header class="cy-header">
    <div class="container-fluid">
        <div class="cy-header-inner">

            {{-- LEFT: Title --}}
            <div class="cy-header-left">
                <div class="cy-header-pre">// {{ strtoupper($pageTitle ?? 'Dashboard') }}</div>
                <h1 class="cy-header-title">{{ $pageTitle ?? 'Default Title' }}</h1>
            </div>

            {{-- RIGHT: Actions --}}
            <div class="cy-header-right">
                @isset($createRoute)
                    @can($createPermission ?? '')
                        <a href="{{ $createRoute }}" class="cy-hbtn primary">
                            <i class="bi bi-plus-lg"></i>
                            <span>{{ $createText ?? 'Create' }}</span>
                        </a>
                    @endcan
                @endisset

                @isset($backRoute)
                    @can($backPermission ?? '')
                        <a href="{{ $backRoute }}" class="cy-hbtn">
                            <i class="bi bi-arrow-left"></i>
                            <span>{{ $backText ?? 'Back' }}</span>
                        </a>
                    @endcan
                @endisset
            </div>

        </div>
    </div>
</header>
