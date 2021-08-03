const login = document.querySelector('input[type="submit"]');
var status;
login.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('form'))
    fetch('http://localhost/Restful_login/backend/model.php', {
        method: 'POST',
        body: formData,
        credentials: 'include'    
    })
    .then( res => {
        status = res.status
        return res.text()
    })
    .then( data => {
        alert(data);
        if(status == 200)
            location.href = "./index.html";
        
    })
    .catch(err => { alert(err); })

})