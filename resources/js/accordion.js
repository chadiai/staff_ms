document.addEventListener('DOMContentLoaded', function() {
    const accordions = document.querySelectorAll('.accordion-header');
    for (let i = 0; i < accordions.length; i++) {
        const accordionHeader = accordions[i];
        accordionHeader.addEventListener('click', toggleAccordion);
    }

    const buttonsEdit = document.querySelectorAll('.accordion-header button');
    for (let j = 0; j < buttonsEdit.length; j++) {
        buttonsEdit[j].addEventListener('click',function (event) {
            event.stopPropagation();
        });
    }

    const buttonDelete = document.querySelectorAll('.accordion-header .delete-button');
    for (let j = 0; j < buttonDelete.length; j++) {
        buttonDelete[j].addEventListener('click',function (event) {
            event.stopPropagation();
        });
    }

    function toggleAccordion(event) {
        console.log('Accordion toggled');
        const accordionHeader = event.currentTarget;
        const accordionContent = accordionHeader.nextElementSibling;
        if (accordionContent.style.display === "none") {
            accordionContent.style.display = "block";
        } else {
            accordionContent.style.display = "none";
        }
        const icons = accordionHeader.querySelectorAll('svg');
        icons[2].classList.toggle('rotate-180');
    }
});
