async function getAuthToken() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const response = await fetchAsync(`${window.APP_URL}/api/auth/login`, {
        email,
        password,
    });
    if (response.success) {
        localStorage.setItem("token", response.data.token);
    }
}

async function fetchAsync(url, params) {
    let response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(params),
        mode: "cors",
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*",
        },
    });
    let data = await response.json();
    return data;
}
