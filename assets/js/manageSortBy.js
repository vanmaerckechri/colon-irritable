window.addEventListener("DOMContentLoaded", (event) => {

	class ManageSortBy
	{
		constructor()
		{
			this.vosFav = document.getElementById('vosFav');
			this.vosRea = document.getElementById('vosRea');
			this.autres = document.getElementById('autres');

			this.userFilters = [];

			this.entrees = document.getElementById('entrees');
			this.platsPrincipaux = document.getElementById('platsPrincipaux');
			this.desserts = document.getElementById('desserts');

			this.commonFilters = [];
		}

		updateFilterDetails()
		{
			let filtrerDetail = document.getElementById('filtrerDetail');
			let userFilter = '';
			let commonFilter = '';

			for (let g = 0; g < 2; g ++)
			{
				let filters = g === 0 ? this.userFilters : this.commonFilters;
				let length = filters.length;
				let count = 0;
				for (let i = 0; i < length; i ++)
				{
					let filter = filters[i];

					if (filter.checked)
					{
						let content = filter.parentNode.innerText;
						count += 1;
						if (filters === this.userFilters)
						{						
							userFilter += content + ', ';
						}
						else
						{						
							commonFilter += content + ', ';
						}
					}
				}

				if (count === length || count === 0)
				{
					if (filters === this.userFilters && this.vosFav)
					{	
						userFilter = 'toutes | ';
					}
					else if (filters === this.commonFilters)
					{
						commonFilter = 'tous types de plat';
					}
				}
			}

			if (userFilter.substring(userFilter.length - 2, userFilter.length) === ', ')
			{
				userFilter = userFilter.substring(0, userFilter.length - 2);
				userFilter += ' | ';
			}
			if (commonFilter.substring(commonFilter.length - 2, commonFilter.length) === ', ')
			{
				commonFilter = commonFilter.slice(0, commonFilter.length - 2);
			}

			filtrerDetail.innerText = ' (' + userFilter + commonFilter + ')';
		}

		initFilters(filters)
		{
			let forceCheckAll = true;
			for (let i = filters.length - 1; i >= 0; i--)
			{
				let filter = filters[i];
				if (filter.checked)
				{
					forceCheckAll = false;
				}

				filter.addEventListener('change', ()=>
				{
					this.updateFilterDetails();
				});
			}
			this.updateFilterDetails();

			if (forceCheckAll)
			{
				for (let i = filters.length - 1; i >= 0; i--)
				{
					let filter = filters[i];
					filter.checked = true;
				}
			}

		}

		init()
		{
			if (this.vosFav && this.vosRea && this.autres)
			{
				this.userFilters = [
					this.vosFav,
					this.vosRea,
					this.autres
				]
			}

			if (this.entrees && this.platsPrincipaux && this.desserts)
			{
				this.commonFilters = [
					this.entrees,
					this.platsPrincipaux,
					this.desserts
				]
			}

			this.initFilters(this.userFilters);
			this.initFilters(this.commonFilters);
		}
	}
	let manageSortBy = new ManageSortBy();
	manageSortBy.init();
});