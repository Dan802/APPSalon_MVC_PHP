const response = await fetch( url, {
        method : 'POST',
        body : datos, 
    }); 
    
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const data = await response.json();
    // Use the parsed JSON data here
    console.log(data);