const sign_up = document.querySelector('.signup');
const log_in = document.querySelector('.login');
const up_date = document.querySelector('.update');
const log_out = document.querySelector('.logout');
const un_sub = document.querySelector('.delete');

sign_up.addEventListener('click', ()=>{location.href = "./signup.html"});
log_in.addEventListener('click', ()=>{location.href = "./login.html"});
up_date.addEventListener('click', ()=>{location.href = "./update.html"});

log_out.addEventListener('click', ()=>{
    fetch('http://localhost/Restful_login/backend/model.php', { 
        credentials:'include',
        method: 'POST'
        
    })
    .then( res => res.text()) 
    .then( data =>{
        alert(data)
        location.href = './index.html';
    })
    .catch(err => { alert(err); })
});

un_sub.addEventListener('click', ()=>{
    fetch('http://localhost/Restful_login/backend/model.php', { 
        credentials:'include',
        method: 'GET'
        
    })
    .then( res => res.text()) 
    .then( data =>{
        alert(data)
        location.href = './index.html';
    })
    .catch(err => { alert(err); })
});
