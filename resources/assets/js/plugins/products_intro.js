/**
 * Created by ivan.li on 13/06/2017.
 */
function startTour(callback) {
    var intro = introJs();
    intro.setOptions({
        steps: [
            {
                element: '.add-category-container',
                intro: "Start with naming the Category. You can add multiple Categories. More Category examples: Running Shoes, Eye Liner or Books."
            },
            {
                element: '.add-product-container',
                intro: "Now add Products within the Category. You can add multiple products. More product examples, considering the Running Shoes Category: Nike Zoom, Mizuno Wave Raider.",
            },
            {
                element: '.category-th > .btn-chart',
                intro: 'Create Category & Product Charts based on a period (e.g. month) and granularity (e.g. day). You can also add it to your Dashboard to easily visualise past and current price trends.',
            },
            {
                element: '.category-th > .btn-report',
                intro: "Set your Email reports at your preferred frequency and time. We'll deliver the report directly to your inbox!",
            },
            {
                element: '.category-th > .btn-delete-category',
                intro: 'You can delete Categories & Products easily through here.'
            },
            {
                element: '#btn-help',
                intro: 'You can always check our FAQ, Tutorials or contact us in case you have questions or concerns.'
            }
        ]
    });
    intro.start();
}