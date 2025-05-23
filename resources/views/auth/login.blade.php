<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-[#d1d1d1] min-h-screen flex flex-col">
  <header class="bg-[#eaf4ff] rounded-b-2xl p-4">
    <h1 class="text-xl">
      <span class="font-extrabold italic text-black">Kost</span>
      <span class="font-extrabold italic text-[#7a7f95]"> Lolita</span>
    </h1>
  </header>

  <main class="flex-grow flex justify-center items-center p-4">
    <form
      class="bg-[#eaf4ff] rounded-2xl p-8 w-full max-w-xs"
      autocomplete="off"
      aria-label="Login form"
    >
      <h2 class="text-2xl font-semibold mb-6 text-center">Login</h2>

      <label for="email" class="block font-semibold mb-1">Email Addres</label>
      <input
        id="email"
        type="email"
        placeholder="Enter your email"
        class="w-full rounded-full border border-gray-700 shadow-[1px_1px_3px_rgba(0,0,0,0.3)] px-4 py-2 mb-5 text-sm placeholder-gray-500 focus:outline-none"
      />

      <label for="password" class="block font-semibold mb-1">Password</label>
      <input
        id="password"
        type="password"
        placeholder="Enter your password"
        class="w-full rounded-full border border-gray-700 shadow-[1px_1px_3px_rgba(0,0,0,0.3)] px-4 py-2 mb-6 text-sm placeholder-gray-500 focus:outline-none"
      />

      <button
        type="submit"
        class="w-full bg-[#1f3554] text-[#eaf4ff] font-extrabold rounded-full py-2 text-lg"
      >
        Login
      </button>
    </form>
  </main>
</body>
</html
