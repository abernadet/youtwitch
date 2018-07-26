(function($){

/*
*	Lire la documentation https://github.com/js-cookie/js-cookie
*	Ajouter un cookie sur le poste de l'utilisateur s'il clique sur le bouton OK.
* 	Lorsqu'il clique sur OK on efface la cookie bar et elle ne revient pas au
*	chargement.
*	Le cookie doit porter le nom : cookie_webforce et la valeur : clicked
* 	et une durée de validité de : 2 jours.
*/

	if(Cookies.get('cookie_webforce') === undefined){
		$('body').append('<div id="cookie_bar" class="cookie_bar">' +
			'Afin de continuer à améliorer la protection de vos données personnelles, nous avons mis à jour notre <a href="{{ path(\'rgpd\') }}">politique de confidentialité</a>.' +
			'<button id="cookie_btn" class="cookie_btn btn btn-orange">J\'accepte</button></div>');
		$('#cookie_btn').click(function(){
			$('#cookie_bar').fadeOut();
			Cookies.set('cookie_webforce', 'clicked', {expires: 2});
		});
	}


})(jQuery);