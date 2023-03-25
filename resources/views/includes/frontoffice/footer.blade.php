<!-- =======================Footer START -->
<footer class="bg-dark pt-5">
	<div class="container">
		<!-- About and Newsletter START -->
		<div class="row pt-3 pb-4">
			<div class="col-md-3">
				<img src="/assets/images/blanc.png" alt="footer logo" style="height: 65px;">
			</div>
			<div class="col-md-5">
				<p class="text-muted">
					Nous sommes Togo Actualité, l’information en temps réel sur le Togo et l’Afrique.Contactez nous sur : <a href="tel:+330614305786" class="text-reset"><u>+33 06 14 30 57 86</u></a> ou sur <br> <a href="tel:+22899565788" class="text-reset"><u>+228 99 56 57 88</u></a>
					ou encore par email sur <a href="mailto:contact@togoactualite.com" class="text-reset"><u>contact@togoactualite.com</u></a>
				</p>
			</div>
			<div class="col-md-4">
				<div id="newsletter"></div>
			</div>
		</div>
		<!-- About and Newsletter END -->

		<!-- Divider -->
		<hr>

		<!-- Widgets START -->
		<div class="row pt-5">
			<!-- Footer Widget -->
			

			<!-- Footer Widget -->
			<div class="col-md-6 col-lg-6 mb-4">
				<h5 class="mb-4 text-white">Mes catégories</h5>
				<div class="row">
					@for ($i=1; $i<= count($categories) - 1; $i++ )
					
						@if ( $i <= 10)

							<div class="col-4">
								<ul class="nav flex-column text-primary-hover">
									<li class="nav-item"><a class="nav-link pt-0" href="/{{ $categories[$i]['slug'] }}">{{$categories[$i]['name']}}</a></li>
								</ul>
							</div>
							
						@elseif ( $i > 10 && $i <= 20)
							
							<div class="col-4">
								<ul class="nav flex-column text-primary-hover">
									<li class="nav-item"><a class="nav-link pt-0" href="/{{ $categories[$i]['slug'] }}">{{$categories[$i]['name']}}</a></li>
								</ul>
							</div>

						@else

							<div class="col-4">
								<ul class="nav flex-column text-primary-hover">
									<li class="nav-item"><a class="nav-link pt-0" href="/{{ $categories[$i]['slug'] }}">{{$categories[$i]['name']}}</a></li>
								</ul>
							</div>

						@endif

					@endfor
					

				</div>
			</div>

			<!-- Footer Widget -->
			<div class="col-sm-6 col-lg-3 mb-4">
				<h5 class="mb-4 text-white"> Nos réseaux sociaux</h5>
				<ul class="nav flex-column text-primary-hover">
					<li class="nav-item"><a class="nav-link pt-0" href="https://wa.link/qf0v9s" target="_blank"><i class="fab fa-whatsapp fa-fw me-2"></i>WhatsApp</a></li>
					<li class="nav-item"><a class="nav-link" href="https://youtube.com" target="_blank"><i class="fab fa-youtube fa-fw me-2"></i>YouTube</a></li>
					<li class="nav-item"><a class="nav-link" href="https://twitter.com/Togoactualite" target="_blank"><i class="fab fa-twitter-square fa-fw me-2"></i>Twitter</a></li>
					<li class="nav-item"><a class="nav-link" href="https://fr.linkedin.com/in/togoactualite-togo-actualit%C3%A9-3a076648" target="_blank"><i class="fab fa-linkedin fa-fw me-2"></i>Linkedin</a></li>
					<li class="nav-item"><a class="nav-link" href="https://www.facebook.com/Togoactualite-148480121847124" target="_blank"><i class="fab fa-facebook-square fa-fw me-2"></i>Facebook</a></li>
				</ul>
			</div>

			<!-- Footer Widget -->
			<div class="col-sm-6 col-lg-3 mb-4">
				<h5 class="mb-4 text-white">Notre application mobile</h5>
				<p class="text-muted">Téléchargez notre application et recevez les dernières alertes d'actualité et les derniers titres et articles quotidiens près de chez vous.</p>
				<div class="row g-2">
					<div class="col">
						<a href="#"><img class="w-100" src="/assets/images/app-store.svg" alt="app-store"></a>
					</div>
					<div class="col">
						<a href="#"><img class="w-100" src="/assets/images/google-play.svg" alt="google-play"></a>
					</div>
				</div>
			</div>
		</div>
		<!-- Widgets END -->

		<!-- Hot topics START -->
		<div id="tags"></div>
		<!-- Hot topics END -->
	</div>

	<!-- Footer copyright START -->
	<div class="bg-dark-overlay-3 mt-5">
		<div class="container">
			<div class="row align-items-center justify-content-md-between py-4">
				<div class="col-md-12">
					<!-- Copyright -->
					<div class="text-center text-md-start text-primary-hover text-muted">©2023 <a href="https://www.togoactualite.com/" class="text-reset btn-link" target="_blank">Togo actualités</a>.Tous droits reservés.
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<!-- Footer copyright END -->
</footer>
<!-- =======================Footer END -->

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short"></i></div>