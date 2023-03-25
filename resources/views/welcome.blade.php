@extends('layouts.frontoffice')

@section('title') Page d' Accueil @endsection

@section('content')
	<main>
		<!-- =======================Trending START -->
		<section class="py-2">
			<div class="container">
				<div class="row g-0">
					<div class="col-12 bg-primary bg-opacity-10 p-2 rounded">
						<div class="d-sm-flex align-items-center text-center text-sm-start">
							<!-- Title -->
							<div class="me-3">
								<span class="badge bg-primary p-2 px-3">Annonces</span>
							</div>
							<!-- Slider -->
							<div class="tiny-slider arrow-end arrow-xs arrow-white arrow-round arrow-md-none">
								<div class="tiny-slider-inner"
									data-autoplay="true"
									data-hoverpause="true"
									data-gutter="0"
									data-arrow="true"
									data-dots="false"
									data-items="1">
									<!-- Slider items -->
									<div> <a href="#" class="text-reset btn-link">The most common business debate isn't as black and white as you might think</a></div>
									<div> <a href="#" class="text-reset btn-link">How the 10 worst business fails of all time could have been prevented </a></div>
									<div> <a href="#" class="text-reset btn-link">The most common business debate isn't as black and white as you might think </a></div>
								</div>
							</div>
						</div>
					</div>
				</div> <!-- Row END -->
			</div>
		</section>
		<!-- =======================Trending END -->
		<br>
	</main>
@endsection