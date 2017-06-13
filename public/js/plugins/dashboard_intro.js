/**
 * Created by ivan.li on 13/06/2017.
 */
function startTour() {
    var intro = introJs();
    intro.setOptions({
        steps: [
            {
                element: '#btn-add-chart',
                intro: "This is your Dashboard. You can Add a Chart this link."
            },
            {
                element: '.placeholder-content-container',
                intro: "You can choose your own Chart characteristic and add it to the Dashboard",
            },
            {
                element: '#btn-head-products',
                intro: "If you want to add your own products to track, let's go to the PRODUCTS page and get started.",
            }
        ]
    });
    intro.start();
}