    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('api_token');
            
            if (!token) {
                   window.location.href = '{{ route('login') }}'; 
            }
        });
    </script>