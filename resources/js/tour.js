import Driver from 'driver.js';
import 'driver.js/dist/driver.min.css';

// Meal page tour
document.getElementById('start-meal')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#filter',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can apply filters to update the list',
                        position: 'top'
                    }
                },
                {
                    element: '#namefilter',
                    popover: {
                        title: 'Filter by name',
                        description: 'Here you can search the meal plan by entering a name or a date',
                        position: 'top'
                    }
                },
                {
                    element: '#typefilter',
                    popover: {
                        title: 'Filter by type',
                        description: 'You can change between different meal types!',
                        position: 'bottom'
                    }
                },
                {
                    element: '#perpagefilter',
                    popover: {
                        title: 'Number of meal plans',
                        description: 'In this dropdown you can change the amount of meal plans that are displayed on the screen.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#bydate',
                    popover: {
                        title: 'Filter by date',
                        description: 'Here you can filter on the date ascending or descending',
                        position: 'top'
                    }
                },
                {
                    element: '#newbutton',
                    popover: {
                        title: 'New meal plan',
                        description: 'Clicking here opens a popup where you can start scheduling a new meal',
                        position: 'top'
                    },
                    onNext: () => {
                        if (window.getComputedStyle(document.getElementById('eventsectionsm')).display === 'none') {
                            driver.moveNext();
                        }
                    }
                },
                {
                    element: '#eventsectionsm',
                    popover: {
                        title: 'List',
                        description: 'In this section all the meal plans will be listed. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    },
                    onNext: () => {
                        if (window.getComputedStyle(document.getElementById('eventsection')).display === 'none') {
                            driver.moveNext();
                        }
                    }
                },
                {
                    element: '#eventsection',
                    popover: {
                        title: 'List',
                        description: 'In this section all the meal plans will be listed. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    }
                }
            ]);
            driver.start();
        }
    );

// Event tour
document.getElementById('start-event')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#filter',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can apply filters to update the list',
                        position: 'top'
                    }
                },
                {
                    element: '#namefilter',
                    popover: {
                        title: 'Filter by name',
                        description: 'Here you can search by entering a name',
                        position: 'top'
                    }
                },
                {
                    element: '#supfilter',
                    popover: {
                        title: 'Filter by category',
                        description: 'Here you can select the category ',
                        position: 'top'
                    }
                },
                {
                    element: '#subfilter',
                    popover: {
                        title: 'Filter by sub category',
                        description: 'If you want you can also specify the sub category. ',
                        position: 'top'
                    }
                },
                {
                    element: '#perpagefilter',
                    popover: {
                        title: 'Amount of tasks',
                        description: 'In this dropdown you can change the amount of events that are displayed on the screen.',
                        position: 'top'
                    }
                },
                {
                    element: '#orderfilter-1',
                    popover: {
                        title: 'Filter by date',
                        description: 'You can order by date or by name',
                        position: 'top'
                    }
                },
                {
                    element: '#orderfilter-2',
                    popover: {
                        title: 'Order direction',
                        description: 'Here you can specify the order direction to ascending or descending.',
                        position: 'top'
                    }
                },
                {
                    element: '#newbutton',
                    popover: {
                        title: 'Add button',
                        description: 'When you click on this button, a new section will popup where you can fill in all the details. If all the required data are entered you can click on create to add it to the system.',
                        position: 'top'
                    },
                    onNext: () => {
                        if (window.getComputedStyle(document.getElementById('eventsectionsm')).display === 'none') {
                            driver.moveNext();
                        }
                    }
                },
                {
                    element: '#eventsectionsm',
                    popover: {
                        title: 'List',
                        description: 'In this section all the events will be listed. You can click on the name of the event to get a more detailed view. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    },
                    onNext: () => {
                        if (window.getComputedStyle(document.getElementById('eventsection')).display === 'none') {
                            driver.moveNext();
                        }
                    }
                },
                {
                    element: '#eventsection',
                    popover: {
                        title: 'View all tasks',
                        description: 'In this section all the tasks will be listed. You can click on the name of the task to get a more detailed view. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    }
                }
            ]);
            driver.start();
        }
    );

// Invoice page tour
document.getElementById('start-invoice')
    ?.addEventListener('click', function (event) {
            let driver = new Driver({opacity: 0.75,});
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#filter',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can apply filters to update the list of invoices.',
                        position: 'top'
                    }
                },
                {
                    element: '#namefilter',
                    popover: {
                        title: 'Filter by invoice title or number',
                        description: 'Here you can search for an invoice by entering its title (name) or invoice number.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#supfilter',
                    popover: {
                        title: 'Filter by category',
                        description: 'You can filter the invoices based on the category they belong to.',
                        position: 'top'
                    }
                },
                {
                    element: '#subfilter',
                    popover: {
                        title: 'Filter by sub category',
                        description: 'You can filter the invoices based on the sub-category they belong to as well.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#statusfilter',
                    popover: {
                        title: 'Filter by payment status',
                        description: 'Filter the invoices based on payment status. You can choose to view only paid invoices, or only unpaid invoices, or both.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#archiving',
                    popover: {
                        title: 'View (un)archived invoices',
                        description: 'You can choose to view either archived or unarchived invoices by toggling the switch. You can edit, archive, and download unarchived invoices. And you can permanently delete or restore archived invoices.',
                        position: 'top'
                    }
                },
                {
                    element: '#orderfilter-1',
                    popover: {
                        title: 'Order invoices',
                        description: 'You can order invoices based on different attributes like the title (alphabetically), submission date, due date, and amount to be paid.',
                        position: 'top'
                    }
                },
                {
                    element: '#orderfilter-2',
                    popover: {
                        title: 'Order direction',
                        description: 'Here you can specify the order direction to ascending or descending.',
                        position: 'top'
                    }
                },
                {
                    element: '#perpagefilter',
                    popover: {
                        title: 'Number of invoices shown on each page',
                        description: 'In this dropdown you can change the amount of invoices that are displayed on the screen.',
                        position: 'top'
                    }
                },
                {
                    element: '#submitNewInvoice',
                    popover: {
                        title: 'Submit a new invoice',
                        description: 'Click on this button to submit a new invoice. When you click on it, you will be redirected to the "submit invoice" page where you can fill in all the relevant information about the invoice.',
                        position: 'top'
                    }
                },
                {
                    element: '#invoicesection',
                    popover: {
                        title: 'List',
                        description: 'In this section all the invoices will be listed. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    }
                }
            ]);
            driver.start();
        }
    );

// Schedule (calendar) page tour
document.getElementById('start-schedule')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '.fc-header-toolbar',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Tool bar',
                        description: 'This section is the toolbar of the calendar, here you can change the week/month. You can go back to today by clicking the button "Today". It is also possible to change the calendar view to month or list! ',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#colorpicker',
                    popover: {
                        title: 'Event colors',
                        description: "You can change the colors of the events. Don't forget to apply by clicking the button <i>APPLY COLORS</i> !",
                        position: 'left'
                    }
                },
            ]);
            driver.start();
        }
    );

// Users page tour
document.getElementById('start-users')
    ?.addEventListener('click', function (event) {
            let driver = new Driver({opacity: 0.75,});
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#userSearch',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can apply filters to search users based on the given criteria',
                        position: 'bottom'
                    }
                },
                {
                    element: '#filter',
                    popover: {
                        title: 'Filter',
                        description: 'You can filter on the name of the user or their role',
                        position: 'bottom'
                    }
                },
                {
                    element: '#activeFilter',
                    popover: {
                        title: 'Active or not',
                        description: 'You can filter on active, inactive or all users',
                        position: 'bottom'
                    }
                },
                {
                    element: '#numberOfUsers',
                    popover: {
                        title: 'Change amount of users',
                        description: 'In this dropdown you can change the amount of users that are displayed on the screen.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#createUser',
                    popover: {
                        title: 'Create a new user',
                        description: 'Clicking here, you can create a new user account for the system',
                        position: 'bottom'
                    }
                },
                {
                    element: '#manageStaffRoles',
                    popover: {
                        title: 'Manage staff roles',
                        description: 'Clicking here, you can manage and create staff roles to assign to users',
                        position: 'bottom'
                    }
                },
                {
                    element: '#userCards',
                    popover: {
                        title: 'View users',
                        description: 'Here you can view all users based on your filtering criteria. You can click on a user to view more information. You can click on the trash icon to delete a user, or the pencil icon to edit a user. ',
                        position: 'top'
                    }
                }

            ]);
            driver.start();
        }
    );

// Meals page tour
document.getElementById('start-meals')
    ?.addEventListener('click', function (event) {
            let driver = new Driver({opacity: 0.75,});
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#searchMeal',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can search meals by their names',
                        position: 'bottom'
                    }
                },
                {
                    element: '#createMeal',
                    popover: {
                        title: 'Create a new meal',
                        description: 'Fill this in and click on the button to create a new meal for the system',
                        position: 'bottom'
                    }
                },
                {
                    element: '#meals',
                    popover: {
                        title: 'View meals',
                        description: 'Here you can view all meals based on your search criteria. You can click on the trash icon to delete a meal, or the pencil icon to edit a meal. ',
                        position: 'top'
                    }
                }

            ]);
            driver.start();
        }
    );

// FAQ page tour
document.getElementById('start-faq')
    ?.addEventListener('click', function (event) {
            let driver = new Driver({opacity: 0.75,});
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#questionSearch',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can search for a question',
                        position: 'bottom'
                    }
                },

                {
                    element: '#questionCards',
                    popover: {
                        title: 'View question',
                        description: 'Here you can view all question based on your search. ',
                        position: 'top'
                    }
                }

            ]);
            driver.start();
        }
    );

// Categories page tour
document.getElementById('start-categories')
    ?.addEventListener('click', function (event) {
        const driver = new Driver({
            opacity: 0.75,
        });
        event.stopPropagation();


        driver.defineSteps([
            {
                element: '#filter',
                popover: {
                    className: 'first-step-popover-class',
                    title: 'Filter section',
                    description: 'In this section you can apply filters to update the list',
                    position: 'top'
                }
            },
            {
                element: '#namedescriptionfilter',
                popover: {
                    title: 'Filter by category',
                    description: 'Here you can search by entering a name or description',
                    position: 'top'
                }
            },
            {
                element: '#newbutton',
                popover: {
                    title: 'Add button',
                    description: 'Click on this button and fill in the details to add a new category.',
                    position: 'top'
                }
            },
            {
                element: '#orderfilter-1',
                popover: {
                    title: 'Filter by date',
                    description: 'You can order by name or by description.',
                    position: 'top'
                }
            },
            {
                element: '#orderfilter-2',
                popover: {
                    title: 'Order direction',
                    description: 'Here you can specify the order direction to ascending or descending.',
                    position: 'top'
                }
            },
            {
                element: '#perpagefilter',
                popover: {
                    title: 'Change amount of categories',
                    description: 'In this dropdown you can change the amount of categories that are displayed on the screen.',
                    position: 'top'
                },
                onNext: () => {
                    if (window.getComputedStyle(document.getElementById('categoriessectionsm')).display === 'none') {
                        driver.moveNext();
                    }
                }
            },
            {
                element: '#categoriessectionsm',
                popover: {
                    title: 'List',
                    description: 'In this section all the categories will be listed. You can click on the name of the category to get a more detailed view. The icons are buttons! Hover over them to get a description!',
                    position: 'top'
                },
                onNext: () => {
                    if (window.getComputedStyle(document.getElementById('categoriessectionmd')).display === 'none') {
                        driver.moveNext();
                    }
                }
            },
            {
                element: '#categoriessectionmd',
                popover: {
                    title: 'List',
                    description: 'In this section all the categories will be listed. You can click on the name of the category to get a more detailed view. The icons are buttons! Hover over them to get a description!',
                    position: 'top'
                }
            }
        ]);
        driver.start();
    });

// Declare absence page tour
document.getElementById('start-absence')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#from',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Select date',
                        description: 'Here you can select the date and time that your absence will start.',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#until',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Select date',
                        description: 'Here you can select the date and time that your absence will end.',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#reason',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Write reason',
                        description: 'Here you need to specify why you will be absent.',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#save',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Save',
                        description: 'Click here to declare the absence.',
                        position: 'top-center'
                    }
                },
            ]);
            driver.start();
        }
    );

// Absence schedule tour
document.getElementById('start-absence-schedule')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#calendar',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Calendar view',
                        description: 'In this calendar you can see all the absences. Click on the absence to see more info about it.',
                        position: 'bottom-center'
                    }
                },
            ]);
            driver.start();
        }
    );

// Submit invoice page tour
document.getElementById('start-submit-invoice')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#title',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Enter title',
                        description: 'Here you can enter the title for the invoice you want to submit.',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#pay',
                    popover: {
                        title: 'Enter due date to pay',
                        description: 'Enter the due date to pay for the invoice.',
                        position: 'top-center'
                    }
                },
                {
                    element: '#file',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Upload file',
                        description: 'Upload the invoice file.',
                        position: 'top-center'
                    }
                },
                {
                    element: '#amount',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Enter the amount',
                        description: 'Here you can enter the amount of the invoice.',
                        position: 'bottom-center'
                    }
                }, {
                    element: '#number',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Enter the invoice number',
                        description: 'Enter the invoice number for the invoice if it has one.',
                        position: 'top-center'
                    }, onNext: () => {
                        const categoryElement = document.getElementById('category');
                        const categorySmallElement = document.getElementById('categorymd');
                        if (window.innerWidth > 1024 && window.innerWidth <= 1280) {
                            if (categorySmallElement && window.getComputedStyle(categorySmallElement).display !== 'none') {
                            }
                        } else {
                            if (categoryElement && window.getComputedStyle(categoryElement).display !== 'none') {
                                driver.moveNext();
                            }
                        }
                    }
                },
                {
                    element: '#categorymd',
                    popover: {
                        title: 'Choose category',
                        description: 'You can select a category.',
                        position: 'bottom-center'
                    }, onNext: () => {
                        const categoryElement = document.getElementById('category');
                        const categorySmallElement = document.getElementById('categorymd');
                        if (window.innerWidth > 1024 && window.innerWidth <= 1280) {
                            if (categorySmallElement && window.getComputedStyle(categorySmallElement).display !== 'none') {
                                driver.moveNext();
                            }
                        } else {
                            if (categoryElement && window.getComputedStyle(categoryElement).display !== 'none') {

                            }
                        }
                    }
                }, {
                    element: '#category',
                    popover: {
                        title: 'Choose category',
                        description: 'You can select a category.',
                        position: 'bottom-center'
                    }
                },
                {
                    element: '#save',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Submit invoice',
                        description: 'Click here to submit the invoice.',
                        position: 'right'
                    }
                },
            ]);
            driver.start();
        }
    )
;

// Files page tour
document.getElementById('start-file')
    ?.addEventListener('click', function (event) {
            let driver = new Driver();
            event.stopPropagation();

            driver.defineSteps([
                {
                    element: '#filterSection',
                    popover: {
                        className: 'first-step-popover-class',
                        title: 'Filter section',
                        description: 'In this section you can apply filters to update the list of files.',
                        position: 'top'
                    }
                },
                {
                    element: '#nameFilter',
                    popover: {
                        title: 'Filter by file name',
                        description: 'Here you can search for a file by entering its name.',
                        position: 'top'
                    }
                },
                {
                    element: '#categoryFilter',
                    popover: {
                        title: 'Filter by category',
                        description: 'You can filter the files based on the category they belong to.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#subCategoryFilter',
                    popover: {
                        title: 'Filter by sub category',
                        description: 'You can filter the files based on the sub-category they belong to as well.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#archiving',
                    popover: {
                        title: 'View (un)archived files',
                        description: 'You can choose to view either archived or unarchived files by toggling the switch. You can edit, archive, and download unarchived files. And you can permanently delete or restore archived files.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#orderBy',
                    popover: {
                        title: 'Order files',
                        description: 'You can order files based on the name of the file or upload date of it.',
                        position: 'top'
                    }
                },
                {
                    element: '#orderDirection',
                    popover: {
                        title: 'Order direction of files',
                        description: 'You can choose to order files ascending or descending based on the criteria you chose (name or upload date).',
                        position: 'top'
                    }
                },
                {
                    element: '#perPageFilter',
                    popover: {
                        title: 'Number of files shown on each page',
                        description: 'In this dropdown you can change the amount of files that are displayed on the screen.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#uploadNewFile',
                    popover: {
                        title: 'Upload a new file',
                        description: 'Click on this button to upload a new file. When you click on it, you will see a pop-up where you can fill in all the relevant information about the file.',
                        position: 'bottom'
                    }
                },
                {
                    element: '#filesSection',
                    popover: {
                        title: 'List of files',
                        description: 'In this section all the files will be listed. The icons are buttons! Hover over them to get a description!',
                        position: 'top'
                    }
                }
            ]);
            driver.start();
        }
    );
