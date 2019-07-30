/**
 * Wizzy wizard jQuery plugin - Version:  0.6.1
 * @copyright 	&copy; 2005-2019 PHPBoost - 2012 Nenad Kaevik
 * @license 	https://github.com/NenadKaevik/wizzy
 * @link        https://nenadkaevik.github.io/wizzy/
 * @author      Nenad Kaevik
 * @doc         https://xpy.github.io/tipy/
 * @version   	PHPBoost 5.3 - last update: 2019 07 30
 * @since   	PHPBoost 5.2 - 2019 07 29
 *
 * @patch		delete es6 (ie11) / replace wizzy -> wizzard - wz- -> wizard- - wz-wrapper -> wizard-container
*/

(function($){

    $.fn.wizard = function(options) {

        let settings = $.extend({
            stepNumbers: false,
            progressType: 'slide',
            nextClass: '',
            prevClass: '',
            finishClass: '',
        }, options);

        return this.each(function(){
            let elem = $(this);
            let nav = elem.find('.wizard-header ul');
            let navigator = elem.find('.wizard-navigator');
            let content = elem.find('.wizard-inner');

            let btnNext = '<a href="#" class="wizard-btn '+ settings.nextClass +' float-right" data-action="next"><i class="fas fa-2x fa-chevron-circle-right" aria-hidden="true"></i><span class="sr-only">next</span></a>';
            let btnBack = '<a href="#" class="wizard-btn '+ settings.prevClass +'" data-action="back"><i class="fas fa-2x fa-chevron-circle-left" aria-hidden="true"></i><span class="sr-only">prev</span></a>';
            let btnFinish = '<span class="wizard-btn '+ settings.finishClass +' float-right" data-action="finish"> <i class="fas fa-2x fa-finish" aria-hidden="true"></i></span><span class="sr-only">finish</span>';

            let step_links = elem.find('nav ul li a').toArray();
            let step_count = step_links.length;
            let step_status = new Array(step_count);
            let step_content = elem.find('.wizard-step').toArray();
            let link_width = $(step_links[0]).width();
            let step = 0;

            function init(){
                for(i = 1 ; i < step_count ; i++){
                    step_status[i] = 0;
                }
                step_status[0] = 1;
                updateTemplate();
                render();
            }

            function moveProgress(step){
                if(settings.progressType == 'fill'){
                    let progressWidth = link_width * (step + 1);
                    nav.find('.progress').css({'width':progressWidth + 'px'});
                }
                if(settings.progressType == 'slide'){
                    nav.find('.progress').css({'width':link_width + 'px'});
                    let distance = link_width * (step);
                    nav.find('.progress').css({'left':distance + 'px'});
                }

            }

            function updateTemplate(){
                nav.append('<div class="progress"></div>');
                moveProgress(step);
            }

            function react(action){

                if(step >= 0 && step < step_count){
                    if(action === 'next'){
                        step_status[step++] = 1;
                        if(step_status[step] === 0){
                            step_status[step] = 1;
                        }
                        render(step);
                    }
                    else if(action == 'back'){
                        step--;
                        render(step);
                    }
                }

            }

            /**
             * Render out the content
             */
            function render(){
                navigator.html('');

                if(step === 0){
                    navigator.append(btnNext);
                }
                else if(step === step_count-1){
                    navigator.append(btnBack + btnFinish);
                }
                else{
                    navigator.append(btnBack + btnNext);
                }

                elem.find('nav ul li a').removeClass('active-step completed-step');
                for(i = 0 ; i < step ; i++){
                    $(step_links[i]).addClass('completed-step');
                }
                $(step_links[i]).addClass('active-step');

                elem.find('.wizard-body .wizard-step').removeClass('active-step');
                $(step_content[step]).addClass('active-step');

                moveProgress(step);
            }

            /**
             * Click events
             */
            $(elem).on('click', '.wizard-navigator .wizard-btn', function(e){
                e.preventDefault();
                let action = $(this).data('action');
                react(action);
            });

            $(elem).on('click', 'nav ul li ', function(e) {
                e.preventDefault();
                let step_check = $(this).index();
                if(step_status[step_check] === 1 || step_status[step_check] === 2){
                    step = $(this).index();
                    render();
                }
                else{
                    console.log('Check errors');
                }
            });

            init();
        });
    }

}(jQuery));
