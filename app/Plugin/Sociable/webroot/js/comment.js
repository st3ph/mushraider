$.fn.comment = function(options) {
	var defaults = {
		maxComments: 10,
		maxLinks: 5
	};

	var settings = $.extend({}, defaults, options);

	return this.each(function () {
		var liste = $(this).parents('#commentaires').find('ul.listeCommentaires');
		var objListe;
		var page = 1;
		var nbComments = liste.find('li').length;
		var nbPages = Math.ceil(nbComments / settings.maxComments);		
		var $compteur = $(this).find('div.compteur');

		url = settings.url;		
		$(this).submit(function() {
			obj = $(this);
			commentaire = obj.find('#commentaire').val();
			if(commentaire.length > 0) {
				idModel = obj.find('#CommentaireIdModel').val();
				nomModel = obj.find('#CommentaireNomModel').val();
				boutonSubmit = obj.find('input[type="submit"]');
				boutonSubmit.attr('disabled', true);
				boutonSubmit.after('<img class="chargement" src="'+site_url+'sociable/img/spinner.gif" alt="Chargement..." />');
				$.ajax({
					type: "POST",
					url: url,
					data: "m="+nomModel+"&id="+idModel+"&c="+commentaire,
					success: function(msg) {
						if(msg.length) {
							resultat = eval('(' + msg + ')');
							if(typeof resultat.error == 'undefined' || resultat.error.length < 1) {
								// On formate la date
								dates = resultat.commentaire[0].date.split(' ');
								jours = dates[0].split('-');
								heures = dates[1].split(':');
								// On créé l'élément du nouveau commentaire
								output = '<li class="nouveau">';								
									output += '<div class="auteur">';
										if(resultat.utilisateur[0].id > 0) {
											output += 'The '+jours[2]+'/'+jours[1]+'/'+jours[0]+' at '+heures[0]+'h'+heures[1]+' by <span>'+resultat.utilisateur[0].pseudo+'</span>';
										}else {
											output += 'The '+jours[2]+'/'+jours[1]+'/'+jours[0]+' at '+heures[0]+'h'+heures[1]+' by <span>'+resultat.utilisateur[0].pseudo+'</span>';
										}
									output += '</div>';
									output += '<div class="texte">';
										output += resultat.commentaire[0].commentaire;
									output += '</div>';
								output += '</li>';
								liste.prepend(output);
								// On affiche le nouveau commentaire
								if(nbComments > settings.maxComments) {
									liste.find('li.nouveau').show();
									//showPage(1);
								}else {
									liste.find('li.nouveau').fadeIn();
								}
								obj.find('.error-message').remove();
								obj.find('#commentaire').val('');
								$('#ajoutCommentaire .compteur span').text('0');

								nbComments = parseInt($('#commentaires span.nbComments').text()) + 1;
								$('#commentaires span.nbComments').text(nbComments);
								$('#commentaires textarea').animate({
									height: 50
								});
							}else {
								obj.find('.error-message').remove();
								obj.prepend('<div class="error-message">'+resultat.error+'</div>');
							}
						}
						obj.find('.chargement').remove();
						boutonSubmit.removeAttr('disabled');
					}
				});
			}

			return false;
		});

		// Gestion des commentaires trop longs
		$(this).find('textarea').keyup(function(event) {
			var nbChars = parseInt($(this).val().length);

			// Si le commentaire n'est pas trop long, on met à jour le compteur
			if(nbChars <= 1000) {
				$compteur.find('span').text(nbChars);
				$compteur.removeClass('error-message');				
			}else { // Sinon on tronque le commentaire et on scroll à la bonne position
				scrolltop = $(this).attr("scrollTop");
				valeur = $(this).val().substring(0, 1000);
				$(this).val(valeur);
				$compteur.addClass('error-message');
				$(this).attr("scrollTop", scrolltop);
			}
		});

		$(this).find('textarea').focus(function() {
			$(this).animate({
				height: 100
			});
		});

		liste.on('click', '.delete i', function(e) {
			var $self = $(this);
			var $container = $self.parent('div');

			$self.fadeOut(function() {
				$container.find('span').fadeIn();
			});

			$container.on('click', '.cancel', function(e) {
				e.preventDefault();

				$container.off('click', '.cancel');

				$container.find('span').fadeOut(function() {
					$self.fadeIn();
				});
			});

			$container.on('click', '.validate', function(e) {
				e.preventDefault();

				var $self = $(this);
				var id = $self.data('id');

				$container.parents('li').fadeOut(function() {
					$(this).remove();
				});

				$.ajax({
					type: 'POST',
					url: url+'/delete',
					data: 'id='+id,
					success: function(msg) {
					},
					error: function() {
						alert('Something wrong happened');
					}
				});
			});
		});
	});
};