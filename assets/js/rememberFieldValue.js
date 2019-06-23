window.addEventListener("DOMContentLoaded", (event) => {

	class RememberFieldValue
	{
		constructor(formName)
		{
			this.form = document.getElementById(formName);

			this.btn;
			this.fields;

			this.init();
		}

		saveValuesToSession()
		{
	    	for (let ft = this.fields.length - 1; ft >= 0; ft--)
	    	{
	    		let fieldType = this.fields[ft];
	    		for (let f = fieldType.length - 1; f >= 0; f--)
	    		{
		    		let field = fieldType[f];
		    		if (field.id.indexOf('token') === -1)
		    		{		    		
		    			sessionStorage.setItem(field.id, field.value);
		    		}
	    		}
	    	}
		}

		deleteValuesFromSession()
		{
			for (let ft = this.fields.length - 1; ft >= 0; ft--)
	    	{
	    		let fieldType = this.fields[ft];
	    		for (let f = fieldType.length - 1; f >= 0; f--)
	    		{
		    		let field = fieldType[f];
		    		if (field.id.indexOf('token') === -1)
		    		{		    		
		    			sessionStorage.removeItem(field.id);
		    		}
	    		}
	    	}			
		}

		giveValuesToForm()
		{
			for (let ft = this.fields.length - 1; ft >= 0; ft--)
	    	{
	    		let fieldType = this.fields[ft];
	    		for (let f = fieldType.length - 1; f >= 0; f--)
	    		{
		    		let field = fieldType[f];
		    		if (field.id.indexOf('token') === -1)
		    		{		    		
		    			field.value = sessionStorage.getItem(field.id);
		    		}
	    		}
	    	}		
		}

		init()
		{
			if (this.form)
			{
				this.btn = this.form.querySelector('button');

				this.fields = [
					this.form.querySelectorAll('input'),
					this.form.querySelectorAll('textarea')
				]; 
				
				this.giveValuesToForm();
				this.deleteValuesFromSession();
				
				this.btn.addEventListener('click', (e)=> { 
			    	e.preventDefault();
			    	this.saveValuesToSession();
			    	this.form.submit();
		   		});
		    }
		}
	}
	let rememberFieldValue = new RememberFieldValue('commentsForm');
});