<script src="{{ asset('js/roles.js') }}"></script>
<script>
    var create_url   = "{{ route('roles.create') }}";
    var store_url    = "{{ route('roles.store') }}";
    var edit_url     = "{{ url('users/roles/{id}/edit') }}";
    var delete_url   = "{{ url('users/roles/{id}') }}";
</script>
