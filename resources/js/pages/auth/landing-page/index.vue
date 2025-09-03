<template>
  <main>
    <div class="d-flex flex-column min-vh-100">
      <!-- Navigation Bar -->
      <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-fixed w-100"
        style="z-index: 1030; backdrop-filter: blur(10px); background: rgba(0, 0, 0, 0.1) !important;">
        <div class="container-fluid px-6">
          <a class="navbar-brand fw-bold fs-4" href="#">
            {{ setting?.app || 'EduFinance' }} <!-- Menghapus ikon ki-wallet -->
          </a>
          <div class="navbar-nav ms-auto d-flex align-items-center">
            <img alt="Logo" :src="setting?.logo" class="h-50px app-sidebar-logo-default" />
            <router-link to="/sign-in" class="btn btn-primary btn-sm px-4 ms-4">
              <i class="ki-duotone ki-entrance-right me-2">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              Login
            </router-link>
          </div>
        </div>
      </nav>

      <!-- Hero Section -->
      <section id="hero" class="hero-section position-relative overflow-hidden">
        <div class="hero-background"></div>
        <div class="hero-overlay"></div>
        <div class="container-fluid py-20 position-relative">
          <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
              <div class="hero-content text-white fade-in-up">
                <h1 class="display-3 fw-bold mb-4">
                  Revolusi Digital untuk
                  <span class="text-primary">Keuangan Sekolah</span>
                </h1>
                <p class="fs-4 opacity-85 mb-6 lh-lg">
                  {{ taglineText }}
                </p>
                <div class="d-flex flex-wrap gap-4">
                  <router-link to="/sign-in" class="btn btn-primary btn-lg px-6 hover-scale">
                    <i class="ki-duotone ki-rocket me-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                    Mulai Sekarang
                  </router-link>
                  <a href="#features" class="btn btn-outline-light btn-lg px-6 hover-scale smooth-scroll">
                    <i class="ki-duotone ki-down me-2">
                      <span class="path1"></span>
                    </i>
                    Pelajari Lebih Lanjut
                  </a>
                </div>
              </div>
            </div>
            <!-- <div class="col-lg-6">
              <div class="hero-stats fade-in-up" style="animation-delay: 0.3s">
                <div class="row g-4">
                  <div class="col-6" v-for="(stat, index) in stats" :key="index">
                    <div class="stat-card bg-white bg-opacity-10 rounded-4 p-6 text-center hover-scale"
                      :style="{ 'animation-delay': `${0.4 + index * 0.1}s` }">
                      <i :class="['ki-duotone', stat.icon, 'text-success', 'fs-2x', 'mb-3']">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3" v-if="stat.icon === 'ki-chart-line-up'"></span>
                      </i>
                      <div class="fs-2 fw-bold text-white counter" :data-target="stat.value">0</div>
                      <div class="fs-7 text-white opacity-75">{{ stat.label }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
          <!-- Updated transparent box -->
          <div class="transparent-box"></div>
        </div>

        <!-- Scroll Indicator -->
        <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x mb-8">
          <a href="#features" class="text-white opacity-75 hover-opacity-100 smooth-scroll">
            <i class="ki-duotone ki-down fs-2x bounce">
              <span class="path1"></span>
            </i>
          </a>
        </div>
      </section>

      <!-- Features Section -->
      <section id="features" class="features-section py-20" :style="featuresBackgroundStyle">
        <div class="container-fluid">
          <div class="text-center mb-15 fade-in-up">
            <span class="badge badge-primary badge-lg mb-4">Fitur Unggulan</span>
            <h2 class="display-5 fw-bold text-gray-900 mb-4">
              Solusi Lengkap untuk Manajemen Keuangan Sekolah
            </h2>
            <p class="fs-4 text-muted mx-auto" style="max-width: 600px;">
              Kelola semua aspek keuangan sekolah dalam satu platform yang terintegrasi dan mudah digunakan
            </p>
          </div>

          <div class="row g-8">
            <div class="col-xl-4 col-md-6" v-for="(feature, index) in features" :key="index">
              <div class="feature-card card border-0 shadow-lg h-100 hover-scale fade-in-up"
                :style="{ 'animation-delay': `${0.2 + index * 0.1}s` }">
                <div class="card-body text-center p-8">
                  <div class="feature-icon mb-6">
                    <div class="symbol symbol-80px mx-auto">
                      <div class="symbol-label" :class="`bg-light-${feature.color}`">
                        <i :class="['ki-duotone', feature.icon, `text-${feature.color}`, 'fs-2x']">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3" v-if="feature.icon === 'ki-chart-line-up'"></span>
                          <span class="path4" v-if="feature.icon === 'ki-shield-tick'"></span>
                        </i>
                      </div>
                    </div>
                  </div>
                  <h3 class="fs-2 fw-bold text-gray-900 mb-4">{{ feature.title }}</h3>
                  <p class="fs-5 text-muted mb-6">{{ feature.description }}</p>
                  <div class="feature-benefits">
                    <div class="d-flex align-items-center mb-3" v-for="benefit in feature.benefits" :key="benefit">
                      <i class="ki-duotone ki-check text-success me-3 fs-6">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      <span class="text-gray-700">{{ benefit }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Why Choose Us Section -->
      <!-- <section class="why-section py-20 bg-light">
        <div class="container-fluid">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <div class="fade-in-up">
                <span class="badge badge-success badge-lg mb-4">Mengapa Memilih Kami</span>
                <h2 class="display-5 fw-bold text-gray-900 mb-6">
                  Dipercaya oleh Ribuan Sekolah di Indonesia
                </h2>
                <p class="fs-4 text-muted mb-8">
                  Platform kami telah membantu sekolah-sekolah mencapai transparansi dan efisiensi dalam pengelolaan
                  keuangan.
                </p>

                <div class="row g-6">
                  <div class="col-6" v-for="(advantage, index) in advantages" :key="index">
                    <div class="d-flex align-items-start">
                      <div class="symbol symbol-50px me-4">
                        <div class="symbol-label bg-light-primary">
                          <i :class="['ki-duotone', advantage.icon, 'text-primary', 'fs-2']">
                            <span class="path1"></span>
                            <span class="path2"></span>
                          </i>
                        </div>
                      </div>
                      <div>
                        <h4 class="fs-4 fw-bold text-gray-900 mb-2">{{ advantage.title }}</h4>
                        <p class="text-muted">{{ advantage.description }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="text-center fade-in-up" style="animation-delay: 0.3s">
                <div class="bg-primary rounded-3 p-10 position-relative overflow-hidden">
                  <div class="achievement-bg"></div>
                  <div class="row g-6 position-relative">
                    <div class="col-6" v-for="(achievement, index) in achievements" :key="index">
                      <div class="text-white text-center">
                        <i :class="['ki-duotone', achievement.icon, 'text-success', 'fs-3x', 'mb-4']">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>
                        <div class="fs-1 fw-bold counter" :data-target="achievement.value">0</div>
                        <div class="fs-6 opacity-75">{{ achievement.label }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section> -->

      <!-- Articles Section -->
      <!-- <section id="articles" class="articles-section py-20">
        <div class="container-fluid">
          <div class="text-center mb-15 fade-in-up">
            <span class="badge badge-info badge-lg mb-4">Artikel & Berita</span>
            <h2 class="display-5 fw-bold text-gray-900 mb-4">
              Pelajari Lebih Lanjut tentang Pengelolaan Keuangan Sekolah
            </h2>
            <p class="fs-4 text-muted mx-auto" style="max-width: 600px;">
              Dapatkan tips, panduan, dan insight terbaru untuk meningkatkan efisiensi keuangan sekolah Anda
            </p>
          </div>

          <div class="row g-6">
            <div class="col-lg-4 col-md-6" v-for="(article, index) in articles" :key="index">
              <article class="article-card card border-0 shadow-lg h-100 hover-scale fade-in-up"
                :style="{ 'animation-delay': `${0.2 + index * 0.1}s` }">
                <div class="article-image">
                  <img :src="article.image" class="card-img-top" :alt="article.title"
                    style="height: 200px; object-fit: cover;">
                  <div class="article-badge">
                    <span class="badge badge-primary">{{ article.category }}</span>
                  </div>
                </div>
                <div class="card-body p-6 d-flex flex-column">
                  <div class="article-meta mb-3">
                    <span class="text-muted fs-7">
                      <i class="ki-duotone ki-calendar me-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      {{ article.date }}
                    </span>
                  </div>
                  <h3 class="fs-4 fw-bold text-gray-900 mb-3">{{ article.title }}</h3>
                  <p class="text-muted mb-4 flex-grow-1">{{ article.excerpt }}</p>
                  <router-link :to="`/article/${article.id}`" class="btn btn-light-primary btn-sm align-self-start">
                    Baca Selengkapnya
                    <i class="ki-duotone ki-arrow-right ms-2">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </router-link>
                </div>
              </article>
            </div>
          </div>
        </div>
      </section> -->

      <!-- CTA Section -->
      <section class="cta-section py-20 bg-primary position-relative overflow-hidden">
        <div class="cta-background"></div>
        <div class="container-fluid position-relative">
          <div class="text-center text-white fade-in-up">
            <h2 class="display-5 fw-bold mb-4">Siap Memulai Transformasi Digital?</h2>
            <p class="fs-4 opacity-85 mb-8 mx-auto" style="max-width: 600px;">
              Bergabunglah dengan ribuan sekolah yang telah merasakan kemudahan pengelolaan keuangan dengan platform
              kami
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-4">
              <router-link to="/sign-up" class="btn btn-light btn-lg px-8 hover-scale">
                <i class="ki-duotone ki-user-tick me-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
                Login
              </router-link>
              <router-link to="/sign-up" class="btn btn-light btn-lg px-8 hover-scale">
                <i class="ki-duotone ki-user-tick me-2">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
                Daftar Gratis
              </router-link>
            </div>
          </div>
        </div>
      </section>

      <!-- Footer Section -->
      <footer class="footer bg-dark text-white py-15">
        <div class="container-fluid">
          <div class="row g-8">
            <div class="col-lg-4">
              <div class="footer-brand">
                <a class="navbar-brand fw-bold fs-3 text-white mb-4 d-block" href="#">
                  <i class="ki-duotone ki-wallet text-primary fs-2x me-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                  </i>
                  {{ setting?.app || 'EduFinance' }}
                </a>
                <p class="text-muted fs-6 mb-6">
                  Platform terdepan untuk manajemen keuangan sekolah yang modern, transparan, dan efisien.
                </p>
                <div class="d-flex gap-3">
                  <a href="https://www.instagram.com/rvalndsyh_?utm_source=ig_web_button_share_sheet&igsh=MXJ5NHd1Zm54MTZxOQ=="
                    target="_blank" class="btn btn-icon btn-sm btn-light-primary">
                    <i class="ki-duotone ki-instagram fs-6"></i>
                  </a>
                  <a href="https://www.instagram.com/mihrosqolby?igsh=dGJ0eGFqbXByeWxx" target="_blank"
                    class="btn btn-icon btn-sm btn-light-primary">
                    <i class="ki-duotone ki-instagram fs-6"></i>
                  </a>
                  <a href="https://www.instagram.com/adizulfa.23/?utm_source=ig_web_button_share_sheet" target="_blank"
                    class="btn btn-icon btn-sm btn-light-primary">
                    <i class="ki-duotone ki-instagram fs-6"></i>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-md-6">
              <h5 class="text-white fw-bold mb-6">Produk</h5>
              <div class="d-flex flex-column gap-3">
                <a href="#" class="text-muted hover-text-primary">Pembayaran SPP</a>
                <a href="#" class="text-muted hover-text-primary">Laporan Keuangan</a>
                <a href="#" class="text-muted hover-text-primary">Manajemen Siswa</a>
                <a href="#" class="text-muted hover-text-primary">Analitik</a>
              </div>
            </div>
            <div class="col-lg-2 col-md-6">
              <h5 class="text-white fw-bold mb-6">Perusahaan</h5>
              <div class="d-flex flex-column gap-3">
                <a href="#" class="text-muted hover-text-primary">Tentang Kami</a>
                <a href="#" class="text-muted hover-text-primary">Karir</a>
                <a href="#" class="text-muted hover-text-primary">Blog</a>
                <a href="#" class="text-muted hover-text-primary">Kontak</a>
              </div>
            </div>
            <div class="col-lg-2 col-md-6">
              <h5 class="text-white fw-bold mb-6">Dukungan</h5>
              <div class="d-flex flex-column gap-3">
                <a href="#" class="text-muted hover-text-primary">Bantuan</a>
                <a href="#" class="text-muted hover-text-primary">Dokumentasi</a>
                <a href="#" class="text-muted hover-text-primary">FAQ</a>
                <a href="#" class="text-muted hover-text-primary">Live Chat</a>
              </div>
            </div>
            <div class="col-lg-2 col-md-6">
              <h5 class="text-white fw-bold mb-6">Legal</h5>
              <div class="d-flex flex-column gap-3">
                <a href="#" class="text-muted hover-text-primary">Kebijakan Privasi</a>
                <a href="#" class="text-muted hover-text-primary">Syarat & Ketentuan</a>
                <a href="#" class="text-muted hover-text-primary">Keamanan</a>
              </div>
            </div>
          </div>
          <hr class="border-secondary my-8">
          <div class="text-center">
            <p class="text-muted mb-0">&copy; 2025 {{ setting?.app || 'EduFinance' }}. Semua hak cipta dilindungi.</p>
          </div>
        </div>
      </footer>
    </div>
  </main>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useSetting } from '@/services';

// State
const router = useRouter();
const { data: setting } = useSetting();
const { data: newSetting } = useSetting(); // Asumsikan ini sumber baru untuk bg_landingpage

// Computed
const taglineText = computed(() => 'Solusi manajemen keuangan sekolah yang terintegrasi, transparan, dan mudah digunakan untuk semua stakeholder pendidikan.');

const featuresBackgroundStyle = computed(() => ({
  background: 'linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%)',
}));

const heroBackgroundStyle = computed(() => {
  const bgImage = newSetting?.value?.bg_landingpage || '';
  return {
    backgroundImage: bgImage ? `url(${bgImage})` : 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
    backgroundSize: 'cover',
    backgroundPosition: 'center',
    position: 'absolute',
    top: 0,
    left: 0,
    width: '100%',
    height: '100%',
  };
});

// Stats Data
const stats = ref([
  {
    icon: 'ki-bank',
    value: 1250,
    label: 'Sekolah Aktif'
  },
  {
    icon: 'ki-chart-line-up',
    value: 50000,
    label: 'Transaksi/Bulan'
  },
  {
    icon: 'ki-profile-user',
    value: 25000,
    label: 'Siswa Terdaftar'
  },
  {
    icon: 'ki-shield-tick',
    value: 99.9,
    label: 'Uptime %'
  }
]);

// Features Data
const features = ref([
  {
    title: 'Pembayaran Digital',
    description: 'Sistem pembayaran SPP dan biaya sekolah yang mudah, cepat, dan aman dengan berbagai metode pembayaran.',
    icon: 'ki-credit-cart',
    color: 'success',
    benefits: [
      'Multi-channel payment',
      'Real-time verification',
      'Auto-reconciliation',
      'Mobile friendly'
    ]
  },
  {
    title: 'Laporan Keuangan',
    description: 'Dashboard analitik dengan visualisasi data keuangan real-time untuk membantu pengambilan keputusan.',
    icon: 'ki-chart-line-up',
    color: 'primary',
    benefits: [
      'Real-time reporting',
      'Interactive dashboard',
      'Export multiple format',
      'Automated insights'
    ]
  },
  {
    title: 'Manajemen Siswa',
    description: 'Kelola data siswa, riwayat pembayaran, dan komunikasi dengan orang tua dalam satu platform terpadu.',
    icon: 'ki-profile-user',
    color: 'info',
    benefits: [
      'Student profiles',
      'Payment history',
      'Parent notifications',
      'Bulk operations'
    ]
  },
  {
    title: 'Keamanan Data',
    description: 'Sistem keamanan berlapis dengan enkripsi tingkat enterprise dan backup otomatis.',
    icon: 'ki-shield-tick',
    color: 'warning',
    benefits: [
      'End-to-end encryption',
      'Regular backups',
      'Access control',
      'Audit trails'
    ]
  },
  {
    title: 'Notifikasi Otomatis',
    description: 'Sistem notifikasi multi-channel untuk mengingatkan pembayaran dan update penting lainnya.',
    icon: 'ki-notification-bing',
    color: 'danger',
    benefits: [
      'SMS & WhatsApp',
      'Email notifications',
      'Push notifications',
      'Custom templates'
    ]
  },
  {
    title: 'Integrasi API',
    description: 'Mudah diintegrasikan dengan sistem existing sekolah melalui API yang lengkap dan dokumentasi jelas.',
    icon: 'ki-code',
    color: 'dark',
    benefits: [
      'RESTful API',
      'Webhooks support',
      'SDK libraries',
      'Developer portal'
    ]
  }
]);

// Advantages Data
const advantages = ref([
  {
    title: 'Support 24/7',
    description: 'Tim support yang siap membantu kapan saja',
    icon: 'ki-24-hours'
  },
  {
    title: 'Cloud Based',
    description: 'Akses dari mana saja dengan koneksi internet',
    icon: 'ki-cloud'
  },
  {
    title: 'Regular Updates',
    description: 'Fitur dan keamanan yang selalu up-to-date',
    icon: 'ki-arrows-loop'
  },
  {
    title: 'Scalable',
    description: 'Berkembang seiring dengan pertumbuhan sekolah',
    icon: 'ki-arrow-up-right'
  }
]);

// Achievements Data
const achievements = ref([
  {
    icon: 'ki-medal-star',
    value: 0,
    label: 'Sekolah Partner'
  },
  {
    icon: 'ki-heart',
    value: 0,
    label: 'Tingkat Kepuasan'
  },
  {
    icon: 'ki-award',
    value: 0,
    label: 'Penghargaan'
  },
  {
    icon: 'ki-discount',
    value: 0,
    label: 'Efisiensi Biaya'
  }
]);

// Articles Data
const articles = ref([
  {
    id: 1,
    title: 'Strategi Efektif Mengelola Keuangan Sekolah di Era Digital',
    excerpt: 'Pelajari langkah-langkah praktis untuk mengoptimalkan pengelolaan anggaran dan meningkatkan transparansi keuangan sekolah.',
    category: 'Tips',
    date: '15 Jan 2025',
    image: '/assets/article-1.jpg'
  },
  {
    id: 2,
    title: 'Manfaat Digitalisasi Keuangan untuk Pendidikan Modern',
    excerpt: 'Bagaimana teknologi dapat meningkatkan efisiensi, transparansi, dan akuntabilitas dalam pengelolaan keuangan sekolah.',
    category: 'Teknologi',
    date: '12 Jan 2025',
    image: '/assets/article-2.jpg'
  },
  {
    id: 3,
    title: 'Panduan Lengkap Implementasi Sistem Pembayaran Digital',
    excerpt: 'Step-by-step guide untuk mengimplementasikan sistem pembayaran digital yang aman dan user-friendly di sekolah.',
    category: 'Panduan',
    date: '10 Jan 2025',
    image: '/assets/article-3.jpg'
  }
]);

// Counter Animation
const animateCounter = () => {
  const counters = document.querySelectorAll('.counter');
  const observerOptions = {
    threshold: 0.5,
    rootMargin: '0px 0px -100px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const counter = entry.target as HTMLElement;
        const target = parseInt(counter.getAttribute('data-target') || '0');
        const increment = target / 100;
        let current = 0;

        const updateCounter = () => {
          if (current < target) {
            current += increment;
            counter.textContent = Math.ceil(current).toString();
            requestAnimationFrame(updateCounter);
          } else {
            counter.textContent = target.toString();
          }
        };

        updateCounter();
        observer.unobserve(counter);
      }
    });
  }, observerOptions);

  counters.forEach(counter => observer.observe(counter));
};

// Smooth Scroll
const initSmoothScroll = () => {
  document.querySelectorAll('.smooth-scroll').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
};

// Scroll Animations
const initScrollAnimations = () => {
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
      }
    });
  }, observerOptions);

  document.querySelectorAll('.fade-in-up').forEach(el => {
    observer.observe(el);
  });
};

onMounted(() => {
  animateCounter();
  initSmoothScroll();
  initScrollAnimations();
});

// Watch untuk memperbarui background saat newSetting berubah
watch(() => newSetting.value, (newVal) => {
  if (newVal?.bg_landingpage) {
    document.querySelector('.hero-background').style.backgroundImage = `url(${newVal.bg_landingpage})`;
  }
}, { immediate: true });
</script>

<style scoped>
/* Import Keenicons */
@import url('https://preview.keenthemes.com/html/metronic/docs/assets/plugins/global/fonts.bundle.css');

/* Animation Keyframes */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(50px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes bounce {

  0%,
  20%,
  50%,
  80%,
  100% {
    transform: translateY(0);
  }

  40% {
    transform: translateY(-10px);
  }

  60% {
    transform: translateY(-5px);
  }
}

@keyframes float {

  0%,
  100% {
    transform: translateY(0px);
  }

  50% {
    transform: translateY(-20px);
  }
}

@keyframes pulse {

  0%,
  100% {
    transform: scale(1);
  }

  50% {
    transform: scale(1.05);
  }
}

/* Animation Classes */
.fade-in-up {
  opacity: 0;
  transform: translateY(50px);
  transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.fade-in-up.animate {
  opacity: 1;
  transform: translateY(0);
}

.bounce {
  animation: bounce 2s infinite;
}

.hover-scale {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.hover-scale:hover {
  transform: scale(1.05);
}

.hover-opacity-100:hover {
  opacity: 1 !important;
}

.hover-text-primary:hover {
  color: var(--bs-primary) !important;
}

/* Hero Section */
.hero-section {
  position: relative;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.2);
}

.hero-stats .stat-card {
  backdrop-filter: blur(20px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Styling untuk logo di pojok kiri atas */
.app-sidebar-logo-default {
  position: absolute;
  top: 10px;
  /* Jarak dari atas */
  left: 20px;
  /* Jarak dari kiri */
  max-height: 50px;
  /* Pastikan tinggi maksimum sesuai class h-50px */
  object-fit: contain;
  /* Memastikan logo tidak terdistorsi */
  z-index: 1031;
  /* Di atas navbar */
}

/* Pastikan navbar cukup tinggi untuk menampung logo */
.navbar {
  min-height: 70px;
  /* Sesuaikan tinggi navbar jika perlu */
}

/* Responsive adjustment untuk logo */
@media (max-width: 768px) {
  .app-sidebar-logo-default {
    top: 5px;
    /* Mengurangi jarak atas pada layar kecil */
    left: 10px;
    /* Mengurangi jarak kiri pada layar kecil */
    max-height: 40px;
    /* Mengurangi ukuran logo pada layar kecil */
  }
}

/* Posisi dan styling untuk teks */
.hero-content {
  position: relative;
  /* Membuat teks bisa digeser */
  margin-top: -100px;
  /* Geser teks ke atas (sesuaikan nilai ini, misalnya -50px atau -150px) */
  z-index: 2;
  /* Pastikan teks berada di atas kotak */
}

/* Styling untuk kotak transparan */
.transparent-box {
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  width: 100%;
  height: 60%;
  background: rgba(36, 33, 33, 0.8);
  /* Sesuaikan warna jika perlu */
  z-index: 1;
  /* Kotak di bawah teks */
}

.scroll-indicator .bounce {
  animation: bounce 2s infinite;
}

/* Navigation */
.navbar {
  transition: all 0.3s ease;
}

.navbar-scrolled {
  background: rgba(255, 255, 255, 0.95) !important;
  backdrop-filter: blur(20px);
}

/* Features Section */
.features-section {
  position: relative;
}

.feature-card {
  border-radius: 20px;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  background: rgba(255, 255, 255, 0.9);
  backdrop-filter: blur(20px);
}

.feature-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.feature-icon .symbol-label {
  border-radius: 20px;
}

/* Why Section */
.why-section .achievement-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, rgba(255, 255, 255, 0.1) 0%, transparent 50%, rgba(255, 255, 255, 0.1) 100%);
  animation: float 6s ease-in-out infinite;
}

/* Articles Section */
.article-card {
  border-radius: 20px;
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.article-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
}

.article-image {
  position: relative;
  overflow: hidden;
}

.article-image img {
  transition: transform 0.3s ease;
}

.article-card:hover .article-image img {
  transform: scale(1.05);
}

.article-badge {
  position: absolute;
  top: 1rem;
  left: 1rem;
}

.article-meta {
  color: #6c757d;
}

/* CTA Section */
.cta-section {
  background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
}

.cta-background {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
  animation: float 10s ease-in-out infinite;
}

/* Footer */
.footer {
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
}

.footer-brand img {
  filter: brightness(0) invert(1);
}

/* Button Styles */
.btn {
  border-radius: 12px;
  font-weight: 600;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
  transition: left 0.5s;
}

.btn:hover::before {
  left: 100%;
}

.btn-primary {
  background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
  border: none;
  box-shadow: 0 8px 25px rgba(33, 150, 243, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 12px 35px rgba(33, 150, 243, 0.4);
}

.btn-outline-light:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.3);
  transform: translateY(-2px);
}

.btn-light-primary {
  background: rgba(33, 150, 243, 0.1);
  color: #2196F3;
  border: 1px solid rgba(33, 150, 243, 0.2);
}

.btn-light-primary:hover {
  background: rgba(33, 150, 243, 0.2);
  transform: translateY(-2px);
}

.btn-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
}

/* Badge Styles */
.badge {
  border-radius: 20px;
  font-weight: 600;
  letter-spacing: 0.5px;
}

.badge-lg {
  padding: 0.5rem 1rem;
  font-size: 0.875rem;
}

/* Symbol Styles */
.symbol-label {
  border-radius: 15px;
  transition: all 0.3s ease;
}

.card:hover .symbol-label {
  transform: scale(1.1);
}

/* Responsive Design */
@media (max-width: 1200px) {
  .display-3 {
    font-size: 3rem;
  }

  .display-5 {
    font-size: 2.5rem;
  }
}

@media (max-width: 992px) {
  .hero-section {
    padding-top: 8rem;
  }

  .display-3 {
    font-size: 2.5rem;
  }

  .display-5 {
    font-size: 2rem;
  }

  .py-20 {
    padding-top: 4rem !important;
    padding-bottom: 4rem !important;
  }

  .py-15 {
    padding-top: 3rem !important;
    padding-bottom: 3rem !important;
  }
}

@media (max-width: 768px) {
  .hero-section {
    padding-top: 6rem;
  }

  .display-3 {
    font-size: 2rem;
  }

  .display-5 {
    font-size: 1.75rem;
  }

  .fs-4 {
    font-size: 1.1rem !important;
  }

  .btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
  }

  .py-20 {
    padding-top: 3rem !important;
    padding-bottom: 3rem !important;
  }

  .py-15 {
    padding-top: 2rem !important;
    padding-bottom: 2rem !important;
  }

  .px-6 {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
}

@media (max-width: 576px) {
  .hero-section {
    padding-top: 5rem;
  }

  .display-3 {
    font-size: 1.75rem;
  }

  .display-5 {
    font-size: 1.5rem;
  }

  .btn-lg {
    padding: 0.625rem 1.25rem;
    font-size: 0.9rem;
  }

  .card-body {
    padding: 2rem 1.5rem;
  }

  .p-8 {
    padding: 2rem !important;
  }

  .p-6 {
    padding: 1.5rem !important;
  }
}

/* Utility Classes */
.min-vh-100 {
  min-height: 100vh;
}

.py-20 {
  padding-top: 8rem;
  padding-bottom: 8rem;
}

.py-15 {
  padding-top: 6rem;
  padding-bottom: 6rem;
}

.px-6 {
  padding-left: 2rem;
  padding-right: 2rem;
}

.px-8 {
  padding-left: 3rem;
  padding-right: 3rem;
}

.p-8 {
  padding: 3rem;
}

.p-10 {
  padding: 4rem;
}

.mb-15 {
  margin-bottom: 6rem;
}

.g-8>* {
  padding: 2rem;
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
  .bg-light {
    background: #1a1a1a !important;
  }

  .text-gray-900 {
    color: #ffffff !important;
  }

  .text-muted {
    color: #8a8a8a !important;
  }

  .card {
    background: rgba(255, 255, 255, 0.05) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
  }
}

/* Smooth Scrolling */
html {
  scroll-behavior: smooth;
}

/* Loading Animation */
.loading {
  animation: pulse 1.5s ease-in-out infinite;
}

/* Focus States */
.btn:focus,
.btn:focus-visible {
  box-shadow: 0 0 0 0.25rem rgba(33, 150, 243, 0.25);
}

/* Print Styles */
@media print {

  .navbar,
  .scroll-indicator,
  .cta-section,
  .footer {
    display: none !important;
  }

  .hero-section {
    background: none !important;
    color: #000 !important;
  }
}

/* High Contrast Mode */
@media (prefers-contrast: high) {
  .btn-outline-light {
    border-width: 2px;
  }

  .card {
    border-width: 2px;
  }
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }

  .scroll-behavior {
    scroll-behavior: auto;
  }
}

/* Selection Styles */
::selection {
  background: rgba(33, 150, 243, 0.3);
  color: #ffffff;
}

::-moz-selection {
  background: rgba(33, 150, 243, 0.3);
  color: #ffffff;
}

/* Scrollbar Styles */
::-webkit-scrollbar {
  width: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: linear-gradient(135deg, #4CAF50 0%, #2196F3 100%);
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(135deg, #45a049 0%, #1976d2 100%);
}
</style>