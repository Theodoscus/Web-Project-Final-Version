//Get all dropdowns from the document
const dropdowns = document.querySelectorAll('.cat-dropdown');

dropdowns.forEach(dropdown => {
    const select = dropdown.querySelector('.select');
    const caret = dropdown.querySelector('.caret');
    const menu = dropdown.querySelector('.category-select-menu');
    const options = dropdown.querySelectorAll('.category-select-menu li');
    const selected = dropdown.querySelector('.selected');
    
    
    select.addEventListener('click', ()=>{

        select.classList.toggle('select-clicked');
        caret.classList.toggle('caret-rotate');
        menu.classList.toggle('category-select-menu-open');
        

    });

    options.forEach(option => {
        option.addEventListener('click', () => {
            selected.innerText = option.innerText;
            select.classList.remove('select-clicked');
            caret.classList.remove('caret-rotate');  
            menu.classList.remove('category-select-menu-open');
            options.forEach(option => {
                option.classList.remove('active');  

            });
            option.classList.add('active');
        });
    })
})
