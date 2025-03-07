<script>
	var timer;
	countdownManager = {
		// Configuration
		targetTime: new Date('{PUBLISHING_END_DATE}'), // Date cible du compte à rebours (00:00:00)
		displayElement: { // Elements HTML où sont affichés les informations
			day:  null,
			day_lang:  null,
			hour: null,
			hour_lang: null,
			min:  null,
			min_lang:  null,
			sec:  null,
			sec_lang:  null
		},

		// Initialisation du compte à rebours (à appeler 1 fois au chargement de la page)
		init: function(){
			// Récupération des références vers les éléments pour l'affichage
			// La référence n'est récupérée qu'une seule fois à l'initialisation pour optimiser les performances
			this.displayElement.day  = jQuery('#countdown-day');
			this.displayElement.day_lang  = jQuery('#countdown-day-lang');
			this.displayElement.hour = jQuery('#countdown-hour');
			this.displayElement.hour_lang = jQuery('#countdown-hour-lang');
			this.displayElement.min  = jQuery('#countdown-min');
			this.displayElement.min_lang  = jQuery('#countdown-min-lang');
			# IF C_COUNTDOWN_WITH_S #
				this.displayElement.sec  = jQuery('#countdown-sec');
				this.displayElement.sec_lang  = jQuery('#countdown-sec-lang');
			# ENDIF #

			// Lancement du compte à rebours
			this.tick(); // Premier tick tout de suite
			timer = window.setInterval("countdownManager.tick();", 1000); // Ticks suivant, répété toutes les secondes (1000 ms)
		},

		// Met à jour le compte à rebours (tic d'horloge)
		tick: function(){
			// Instant présent
			var timeNow  = new Date();

			// On s'assure que le temps restant ne soit jamais négatif (ce qui est le cas dans le futur de targetTime)
			if( timeNow > this.targetTime ){
				timeNow = this.targetTime;
			}

			// Calcul du temps restant
			var diff = this.dateDiff(timeNow, this.targetTime);

			if ( diff.day > 0 )
			{
				this.displayElement.day.text( diff.day );
			}
			else
			{
				this.displayElement.day.hide();
				this.displayElement.day_lang.hide();
			}

			if ( diff.hour > 0 )
			{
				this.displayElement.hour.text( diff.hour )
			}
			else
			{
				this.displayElement.hour.hide();
				this.displayElement.hour_lang.hide();
			}

			if ( diff.hour == 0 && diff.min  == 0 )
			{
				this.displayElement.min.hide();
				this.displayElement.min_lang.hide();
				# IF NOT C_COUNTDOWN_WITH_S #
					jQuery('#poll-countdown').html('<span id="countdown-remaining-time">' + ${escapejs(@poll.countdown.remaining.time)} + '</span><span id="countdown-less-than-one-minute">' + ${escapejs(@poll.countdown.less.than.one.minute)} + '</span>');
				# ENDIF #
			}
			else
			{
				this.displayElement.min.text( diff.min );
			}

			if ( diff.hour == 0 && diff.min == 0 && diff.sec == 0 )
			{
				//Stop the timer
				window.clearInterval(timer);//Stop the timer
				//Reloading url at the end of countdown
				location.reload(true);
			}
			# IF C_COUNTDOWN_WITH_S #
				else
				{
					this.displayElement.sec.text( diff.sec );
				}
			# ENDIF #
		},

		// Calcul la différence entre 2 dates, en jour/heure/minute/seconde
		dateDiff: function(date1, date2){
			var diff = {}                           // Initialisation du retour
			var tmp = date2 - date1;

			tmp = Math.floor(tmp/1000);             // Nombre de secondes entre les 2 dates
			diff.sec = tmp % 60;                    // Extraction du nombre de secondes
			tmp = Math.floor((tmp-diff.sec)/60);    // Nombre de minutes (partie entière)
			diff.min = tmp % 60;                    // Extraction du nombre de minutes
			tmp = Math.floor((tmp-diff.min)/60);    // Nombre d'heures (entières)
			diff.hour = tmp % 24;                   // Extraction du nombre d'heures
			tmp = Math.floor((tmp-diff.hour)/24);   // Nombre de jours restants
			diff.day = tmp;

			return diff;
		}
	};

	jQuery(function($){
		// Lancement du compte à rebours au chargement de la page
		countdownManager.init();
	});
</script>

<div id="poll-countdown">
	<span id="countdown-remaining-time">{@poll.countdown.remaining.time}</span>
	<span id="countdown-day" >--</span><span id="countdown-day-lang"> {@date.days}</span>
	<span id="countdown-hour">--</span><span id="countdown-hour-lang">{@date.unit.hour}</span>
	<span id="countdown-min" >--</span><span id="countdown-min-lang">{@date.unit.minute}</span>
	# IF C_COUNTDOWN_WITH_S #
		<span id="countdown-sec" >--</span><span id="countdown-sec-lang">{@date.unit.seconds}</span>
	# ENDIF #
</div>
