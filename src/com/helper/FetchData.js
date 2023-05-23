const FetchData = async (url, options = {redirect: 'follow'}) => {
  try {
    let { method = 'GET', formData } = options;
    if(method === 'POST' && !formData)
    {
      const formElement = document.querySelector('#formy');
      formData = new FormData(formElement);
    }
    const requestOptions = { method,
      credentials: 'include'};
    if (method === 'POST' && formData) {
      requestOptions.body = formData;
    }
    const response = await fetch(url, requestOptions);
    const contentType = response.headers.get('Content-Type');
    if (contentType && contentType.includes('application/json')) {
      const data = await response.json();
      console.log('server warning: ' + (data.warning ?? ''));
      return data;
    } else {
      const error = await response.text();
      console.error('server error: ' + error);
    }
  } catch (error) {
    console.error('local error: ' + error);
  }
}
export default FetchData;
