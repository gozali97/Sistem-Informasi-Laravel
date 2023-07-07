@if (session('success'))
    <div class="bs-toast toast fade show bg-success" role="alert" style="width: 100%!important;" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-header">
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".bs-toast").alert('close');
            }, 5000);
        });
    </script>
@endif

@if (session('error'))
    <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".bs-toast").alert('close');
            }, 5000);
        });
    </script>
@endif

@if ($errors->any())
    <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $(".bs-toast").alert('close');
            }, 5000);
        });
    </script>
@endif
