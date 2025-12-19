<button id="debugButton" class="btn btn-button" onclick="">
       Debug 
</button>

    <h2 style="display: none" id="debug"></h2>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const token = localStorage.getItem('api_token');
            const debug = document.getElementById('debug')
            const debugButton = document.getElementById('debugButton')
            //DEBUG
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