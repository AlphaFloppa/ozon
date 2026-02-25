window.addEventListener('load', () => {
    document.querySelector('.logout_button').addEventListener('click', (event) => {
        const CSRFToken = document.querySelector(`meta[name='csrf-token']`);
        fetch(
            '/logout',
            {
                method: 'POST',
                credentials: 'include',
                body: new URLSearchParams(
                    {
                        '_csrf_token': CSRFToken        
                    }
                )
            }
        ).then(
            (response) => { 
                window.location.href = response.url;
            }
        )
    })
});