const lightbox = document.createElement('div');
lightbox.id = 'lightbox';
document.body.appendChild(lightbox);
const img = document.createElement('img');
// img.style.width('100%');
lightbox.appendChild(img);


const images = document.querySelectorAll('.box');
images.forEach(image => {
    image.addEventListener('click', e =>{
        lightbox.classList.add('active');
        
        img.src = image.style.backgroundImage.split('"')[1];
        // console.log(img);
        
    });
});

lightbox.addEventListener('click', e => {
    lightbox.classList.remove('active');
});


// category sorting
const selectedCat = document.querySelectorAll('.li');
selectedCat.forEach(catItem => {
    catItem.addEventListener('click', e => {
        const boxs = document.querySelectorAll('.box');
        const category = catItem.id;
        
        const allCat = document.querySelectorAll('.li');// catItem.shibling.classList.remove('active');;
        allCat.forEach(cat =>{
            if(cat.classList.contains('active')){
                cat.classList.remove('active');
            }
        })

        catItem.classList.add('active');
        // console.log(category);
        boxs.forEach(box => {
            box.style.display = 'none'; 
            if(box.classList.contains(category)){
                box.style.display = "block";
            }
        })
    })
})

