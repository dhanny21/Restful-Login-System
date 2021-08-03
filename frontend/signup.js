const signup = document.querySelector('input[type="submit"]');
var status;
signup.addEventListener('click', () => {
    const formData = new FormData(document.querySelector('form'))
    //fetch('localhost/backend/model.php', {
   //fetch('../backend/model.php', {
    fetch('http://localhost/Restful_login/backend/model.php', { 
        method: 'POST',
        body: formData
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