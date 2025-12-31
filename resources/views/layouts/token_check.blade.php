<button id="debugButton" class="btn btn-button" onclick="">
       Debug 
</button>

    <h2 style="display: none" id="debug"></h2>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('api_token');
            const me_endpoint = 'http://estocar-1.test/api/me'
            //DEBUG
            const debug = document.getElementById('debug')
            const debugButton = document.getElementById('debugButton')
            debugButton.addEventListener('click',() => {
               if (debug.style.display === 'none') {
                    debug.style.display = 'block'
               } else {
                    debug.style.display = 'none'
               } 
            })

            debug.textContent = `Debug(TOKEN):${token}`
            
            if (!token) {
                   window.location.href = '{{ route('login') }}'; 
            }
        });
    </script>