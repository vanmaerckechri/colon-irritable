window.addEventListener("DOMContentLoaded", (event) => {

	class ManageIngAndEtapes
	{
		constructor()
		{
			this.fields = [
				{
					'name': 'etape',
					'type': 'textarea'
				},
				{
					'name': 'ingredient',
					'type': 'input'
				}
			];
		}

		updateLabels(fieldIndex, collectionHolder)
		{
			let field = this.fields[fieldIndex];

			let labels = collectionHolder.querySelectorAll('label');
			for (let i = labels.length - 1; i >= 0; i--)
			{
				labels[i].innerText = field.name + (i + 1);
			}
		}

		deleteRow(fieldIndex, collectionHolder, deleteBtn)
		{
			let field = this.fields[fieldIndex];

			let liToDelete = deleteBtn.parentNode;
			let liToDeletePosition;

			let liCollection = collectionHolder.querySelectorAll('li');
			for (let i = 0, length = liCollection.length; i < length; i++)
			{
				let currentLi = liCollection[i];
				// record position of li to delete
				if (currentLi === liToDelete)
				{
					liToDeletePosition = i;
				}
				// if currentLi is li to delete or if currentLi position is after the li to delete...
				if (i >= liToDeletePosition)
				{
					// if next li exist move next li content to current li ELSE delete current li (only if next li have not a add button)
					if (liCollection[i + 1])
					{
						if (!liCollection[i + 1].querySelector('.add_' + field.name + '_link'))
						{
							let nextLi = liCollection[i + 1];
							nextLi = nextLi.querySelector(field.type);
							currentLi = currentLi.querySelector(field.type);

							currentLi.value = nextLi.value;
						}
						else
						{
							currentLi.remove();
						}
					}
				}
			}
		}

		addDeleteBtn(fieldIndex, collectionHolder, li)
		{
			let field = this.fields[fieldIndex];
	   		let deleteBtn = document.createElement('button');
	   		deleteBtn.setAttribute('class', 'remove-' + field.name);
	   		deleteBtn.innerText = "X";
	   		li.append(deleteBtn);

	   		// delete row event
	   		deleteBtn.addEventListener('click', (e)=> {
	   			e.preventDefault();
				this.deleteRow(fieldIndex, collectionHolder, deleteBtn);
	   		});
		}

		addRow(fieldIndex, collectionHolder, liBtn)
		{
			let field = this.fields[fieldIndex];
		    // Get the data-prototype explained earlier
		    let prototype = collectionHolder.getAttribute('data-prototype');

		    // get the new index
		    let index = parseInt(collectionHolder.getAttribute('data-index'));
		    // Replace '$$name$$' in the prototype's HTML to
		    // instead be a number based on how many items we have
		    let newForm = prototype.replace(/__name__/g, index);
		    // increase the index with one for the next item
		    collectionHolder.setAttribute('data-index', index + 1);
		    
		    // Display the form in the page in an li, before the "Add a tag" link li
		    let newFormLi = document.createElement('li');
			newFormLi.innerHTML = newForm;

			let parent = liBtn.parentNode;
			console.log(parent)
		    parent.insertBefore(newFormLi, liBtn);

			this.addDeleteBtn(fieldIndex, collectionHolder, newFormLi);
		}

		addCreateBtn(fieldIndex, collectionHolder)
		{
			let field = this.fields[fieldIndex];
		   	// create add element btn
			let liBtn = document.createElement('li');
			let addBtn = document.createElement('button');
			addBtn.setAttribute('class', 'add_' + field.name + '_link');
			addBtn.setAttribute('type', 'button')
			addBtn.innerText = '+ 1 ' + field.name;
			liBtn.appendChild(addBtn);

		    // add the "add element btn" to the current ul
		    collectionHolder.appendChild(liBtn);
		    
		    // add row event
		    addBtn.addEventListener('click', ()=> { 
		    	this.addRow(fieldIndex, collectionHolder, liBtn);
		    	this.updateLabels(fieldIndex, collectionHolder);
		    });
		}

		init()
		{
			let fields = this.fields;

			for (let i = fields.length - 1; i >= 0; i--)
			{
				// category name
				let field = fields[i];

			    // Get the ul that holds the collection of tags
			   	let collectionHolder = document.querySelector('ul.' + field.name + 's');

			    // update data-index of the current ul with input length
			    collectionHolder.setAttribute('data-index', collectionHolder.querySelectorAll('li').length);

			   	// Add a delete btn to elem who already exist
			   	let collection = document.querySelector('ul.' +  field.name + 's');
			   	let collectionOld = collection.querySelectorAll('li');
			   	for (let j = collectionOld.length - 1; j >= 0; j--)
			   	{
			   		let li = collectionOld[j];
			   		this.addDeleteBtn(i, collectionHolder, li);
			   	}

		    	this.updateLabels(i, collectionHolder);
			   	this.addCreateBtn(i, collectionHolder)
			}
		}
	}
	let manageIngAndEtapes = new ManageIngAndEtapes();
	manageIngAndEtapes.init();
});