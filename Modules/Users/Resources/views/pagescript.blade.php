<script src="{{ asset('js/users.js') }}"></script>
<script>
    var create_url   = "{{ route('users.create') }}";
    var store_url    = "{{ route('users.store') }}";
    var edit_url     = "{{ url('users/users/{id}/edit') }}";
    var delete_url   = "{{ url('users/users/{id}') }}";
</script>
