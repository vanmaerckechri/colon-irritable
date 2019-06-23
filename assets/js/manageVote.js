window.addEventListener("DOMContentLoaded", (event) => {

	class ManageVote
	{
		constructor(scoreContainerName, numberInputName, formName)
		{
			this.scoreContainer = document.getElementById(scoreContainerName);
			this.form = document.getElementById(formName);
			this.stars;
			this.input;
			this.submitBtn;

			this.init(numberInputName);
		}

		uncheckStars()
		{
			for (let s = this.stars.length - 1; s >= 0; s--)
			{
				let star = this.stars[s];
				star.checked = false;
			}
		}

		giveValueToStars()
		{
			let value = this.input.value;
			this.stars[this.stars.length - value].checked = true;
		}

		giveStarsToInput()
		{
			let score = 0;
			for (let s = this.stars.length - 1; s >= 0; s--)
			{
				let star = this.stars[s];

				if (star.checked === true)
				{
					score = star.value;
					this.input.value = score;
					return;
				}
			}
		}

		isVoted()
		{
			for (let s = this.stars.length - 1; s >= 0; s--)
			{
				let star = this.stars[s];
				if (star.checked === true)
				{
					return true;	
				}
			}
			return false;
		}

		sendAlertSms()
		{
			this.scoreContainer.classList.add('alert');
		}

		init(numberInputName)
		{
			if (this.scoreContainer)
			{
				this.stars = this.scoreContainer.querySelectorAll('input');
				this.input = document.getElementById(numberInputName);
				this.submitBtn = this.form.querySelector('button');

				if (this.input.value)
				{
					this.giveValueToStars();
				}
				else
				{
					this.uncheckStars();
				}

				for (let s = this.stars.length - 1; s >= 0; s--)
				{
					let star = this.stars[s];
					star.addEventListener('change', ()=>{
						this.giveStarsToInput();
					});
				}

				this.submitBtn.addEventListener('click', (e)=> {
					
					e.preventDefault();

					if (!this.isVoted())
					{
						this.sendAlertSms();
					}
					else
					{
						this.form.submit();
					}
				});
			}
		}
	}
	let manageVote = new ManageVote('scoreContainer', 'comment_score', 'commentsForm');
});