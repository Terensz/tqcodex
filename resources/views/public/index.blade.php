@extends('public.layouts.public-body')
@section('content')

<style>
  .splash-container {
    position: relative;
    overflow: hidden;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-size: cover;
    background-repeat: no-repeat;
    transition: background-image 3s ease;
  }

  .navbar {
    padding: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    background-color: rgba(255, 255, 255, 0.8);
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
  }

  .navbar-left {
    display: flex;
    align-items: center;
  }

  .navbar-right {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }

  .navbar-logo {
    width: 23px;
    height: 23px;
    margin-right: 10px;
  }

  .navbar-software-title {
    font-family: 'Cinzel', serif;
    font-size: 1.125rem;
  }

  .navbar-link {
    font-family: 'Cinzel', serif;
    font-size: 1.125rem;
    font-weight: 600;
    color: black;
    margin: 0 8px;
    transition: color 0.3s, text-decoration 0.3s;
  }

  .navbar a:hover {
    color: #4a5568;
    text-decoration: underline;
  }

  @media (max-width: 768px) {
    .navbar {
      flex-direction: column;
      align-items: flex-start;
    }

    .navbar-right {
      width: 100%;
      justify-content: flex-start;
      margin-top: 16px;
    }

    .navbar a {
      margin: 4px 0;
    }
  }

  .splash-textbox {
    text-align: center;
    color: white;
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.9);
    max-width: 800px;
    margin: auto;
    box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
    border-radius: 12px;
  }

  /* Social icons */

  .social-icons-container {
    padding-top: 20px;
    padding-bottom: 10px;
  }

  .social-icons {
    position: fixed;
    bottom: 10px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 15px;
  }

  .social-icons a {
    color: white;
    font-size: 36px;
    transition: color 0.3s ease;
  }

  .social-icons a:hover {
    color: #ccc;
  }

  /* AscLogo */

  .asclogo_container {
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    height: 52px;
    align-items: flex-end;
    padding-right: 20px;
  }

  .asclogo_row {
    display: flex;
    justify-content: flex-start;
    margin-bottom: 2px;
  }

  .asclogo_square {
    width: 10px;
    height: 10px;
    margin-right: 2px;
  }

  .asclogo_square_2 {
    background-color: #131313;
  }


  .asclogo_row:nth-child(1) .asclogo_square_1:nth-child(4) { background-color: #073a6c; }

  .asclogo_row:nth-child(2) .asclogo_square_1:nth-child(3) { background-color: #073a6c; }
  .asclogo_row:nth-child(2) .asclogo_square_1:nth-child(4) { background-color: #1b6dbd; }

  .asclogo_row:nth-child(3) .asclogo_square_1:nth-child(2) { background-color: #073a6c; }
  .asclogo_row:nth-child(3) .asclogo_square_1:nth-child(3) { background-color: #1b6dbd; }
  .asclogo_row:nth-child(3) .asclogo_square_1:nth-child(4) { background-color: #77afe5; }

  .asclogo_row:nth-child(4) .asclogo_square_1:nth-child(1) { background-color: #073a6c; }
  .asclogo_row:nth-child(4) .asclogo_square_1:nth-child(2) { background-color: #1b6dbd; }
  .asclogo_row:nth-child(4) .asclogo_square_1:nth-child(3) { background-color: #77afe5; }
  .asclogo_row:nth-child(4) .asclogo_square_1:nth-child(4) { background-color: #ffffff; }

  .asclogo_row:nth-child(1) .asclogo_square_2:nth-child(1) { background-color: #a1a1a1; }
  .asclogo_row:nth-child(1) .asclogo_square_2:nth-child(2) { background-color: #0a0a0a; }
  .asclogo_row:nth-child(2) .asclogo_square_2:nth-child(1) { background-color: #0a0a0a; }

  .asclogo_square:last-child { margin-right: 0; }




  .dot-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
  }

  .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: #444;
    margin: 0 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .dot.active {
    background-color: white;
  }
</style>

<div class="splash-container" id="splash-container" style="background-image: url('/images/earth-1388003_1280.jpg');">

  <nav class="navbar">
    <div class="navbar-left">
      <span>
        <img class="navbar-logo" src="/images/asclogo.svg" alt="Admin Scale Creator Logo">
      </span>
      <span class="navbar-software-title">Admin Scale Creator</span>
    </div>
    <div class="navbar-right">
      <a class="navbar-link" href="" class="font-cinzel">Termékek</a>
      <a class="navbar-link" href="" class="font-cinzel">Regisztráció</a>
      <a class="navbar-link" href="" class="font-cinzel">Bejelentkezés</a>
      <a class="navbar-link" href="" class="font-cinzel">Dokumentumok</a>
    </div>
  </nav>

  <div class="splash-textbox">
    <div style="display: flex;">


      <div class="asclogo_container">
        <div class="asclogo_row">
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_1"></div>
        </div>
        <div class="asclogo_row">
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
        </div>
        <div class="asclogo_row">
          <div class="asclogo_square asclogo_square_2"></div>
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
        </div>
        <div class="asclogo_row">
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
          <div class="asclogo_square asclogo_square_1"></div>
        </div>
      </div>

      <div>
        <h1 class="text-6xl font-cinzel mb-4">Admin Scale Creator</h1>
      </div>
    </div>
    <div>
      <h3 class="text-2xl font-cinzel">Tudd, hová tartasz</h3>
    </div>

    <div class="dot-container" style="display: none"></div>

    <!-- <div class="social-icons-container">
      <div class="social-icons">
        <a href="https://facebook.com" target="_blank" class="fa-brands fa-facebook"></a>
        <a href="https://x.com" target="_blank" class="fa-brands fa-x-twitter"></a>
        <a href="https://instagram.com" target="_blank" class="fa-brands fa-instagram"></a>
      </div>
    </div> -->
  </div>

  <div class="social-icons">
    <a href="https://facebook.com" target="_blank" class="fa-brands fa-facebook"></a>
    <a href="https://x.com" target="_blank" class="fa-brands fa-x-twitter"></a>
    <a href="https://instagram.com" target="_blank" class="fa-brands fa-instagram"></a>
  </div>

</div>

<script>
  const BackgroundChanger = {
    container: document.getElementById('splash-container'),
    backgrounds: [
      '/images/notepad-1130743_1280.jpg',
      '/images/company-3607510_1280.jpg',
      '/images/indiana-dunes-state-park-1848559_1280.jpg',
      '/images/earth-1388003_1280.jpg',
    ],
    currentIndex: 0,
    dotsContainer: document.querySelector('.dot-container'),

    changeBackground: function(index) {
      this.container.style.backgroundImage = `url(${this.backgrounds[index]})`;
      this.currentIndex = index;
      this.updateDots();
    },

    updateDots: function() {
      const dots = this.dotsContainer.querySelectorAll('.dot');
      dots.forEach((dot, index) => {
        if (index === this.currentIndex) {
          dot.classList.add('active');
        } else {
          dot.classList.remove('active');
        }
      });
    },

    start: function() {
      const dots = this.backgrounds.map((_, index) => {
        const dot = document.createElement('div');
        dot.classList.add('dot');
        if (index === 0) dot.classList.add('active');
        dot.addEventListener('click', () => this.changeBackground(index));
        return dot;
      });

      dots.forEach(dot => this.dotsContainer.appendChild(dot));

      this.changeBackground(this.currentIndex);
      setInterval(() => {
        this.currentIndex = (this.currentIndex + 1) % this.backgrounds.length;
        this.changeBackground(this.currentIndex);
      }, 5000);
    }
  };

  document.addEventListener('DOMContentLoaded', function() {
    BackgroundChanger.start();
  });
</script>

@endsection
