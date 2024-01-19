<script setup lang="ts">
import axios from "@/libs/axios";
import { useQuery } from "@tanstack/vue-query";
import { currency } from "@/libs/utils";

const { data } = useQuery({
  queryKey: ["dashboard", localStorage.getItem("tahun")],
  queryFn: () => axios.post("/dashboard/admin").then((res) => res.data),
});
</script>

<template>
  <main>
    <div class="card mb-10">
      <div
        class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-primary">
        <h3 class="card-title align-items-start flex-column text-white pt-10">
          <span class="fw-bold fs-2x">Anggaran</span>
        </h3>
      </div>
      <div class="card-body mt-n20">
        <div class="mt-n20 position-relative">
          <div class="row g-3 g-lg-6">
            <div class="col-xl-4 col-sm-6">
              <router-link to="/dashboard/realisasi"
                class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-info border-left-5 border-start d-block">
                <div class="symbol symbol-30px me-5 mb-8">
                  <span class="symbol-label">
                    <i class="ki-duotone ki-finance-calculator fs-2x text-info">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                      <span class="path4"></span>
                      <span class="path5"></span>
                      <span class="path6"></span>
                      <span class="path7"></span>
                    </i>
                  </span>
                </div>
                <div class="m-0 d-flex justify-content-between align-items-center">
                  <div>
                    <span class="text-info fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">
                      Rp{{ currency(data?.anggaran.dipa_awal, { notation: "compact", maximumFractionDigits: 2 }) }}
                    </span>
                    <span class="text-gray-500 fw-semibold fs-6">Dipa Awal</span>
                  </div>
                  <i class="fa fa-chevron-right text-info fs-2"></i>
                </div>
              </router-link>
            </div>
            <div class="col-xl-4 col-sm-6">
              <router-link to="/dashboard/realisasi/revisi"
                class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-warning border-left-5 border-start d-block">
                <div class="symbol symbol-30px me-5 mb-8">
                  <span class="symbol-label">
                    <i class="ki-duotone ki-cube-3 fs-2x text-warning">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </div>
                <div class="m-0 d-flex justify-content-between align-items-center">
                  <div>
                    <span class="text-warning fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">
                      <template v-if="data?.anggaran.revisi_anggaran">
                        Rp{{ currency(data?.anggaran.revisi_anggaran, { notation: "compact", maximumFractionDigits: 2 })
                        }}
                      </template>
                      <template v-else>
                        -
                      </template>
                    </span>
                    <span class="text-gray-500 fw-semibold fs-6">Revisi Pagu</span>
                  </div>
                  <i class="fa fa-chevron-right text-warning fs-2"></i>
                </div>
              </router-link>
            </div>
            <div class="col-xl-4 col-sm-6">
              <router-link to="/dashboard/realisasi/sisa"
                class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-success border-left-5 border-start d-block">
                <div class="symbol symbol-30px me-5 mb-8">
                  <span class="symbol-label">
                    <i class="ki-duotone ki-tag fs-2x text-success">
                      <span class="path1"></span>
                      <span class="path2"></span>
                      <span class="path3"></span>
                    </i>
                  </span>
                </div>
                <div class="m-0 d-flex justify-content-between align-items-center">
                  <div>
                    <span class="text-success fw-bolder d-block fs-2qx lh-1 ls-n1 mb-1">
                      Rp{{ currency(data?.anggaran.sisa_anggaran, { notation: "compact", maximumFractionDigits: 2 }) }}
                    </span>
                    <span class="text-gray-500 fw-semibold fs-6">Sisa Anggaran</span>
                  </div>
                  <i class="fa fa-chevron-right text-success fs-2"></i>
                </div>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row row-gap-10 mb-10">
      <div class="col-lg-6">
        <div class="card">
          <div
            class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-light-dark">
            <h3 class="card-title align-items-start flex-column pt-10">
              <span class="fw-bold fs-2x">Pendapatan/Belanja</span>
            </h3>
          </div>
          <div class="card-body mt-n20">
            <div class="mt-n20 position-relative">
              <div class="row g-3 g-lg-6">
                <div class="col-md-6">
                  <router-link to="/dashboard/transaksi/penjualan"
                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-success border-left-5 border-start d-block">
                    <div class="symbol symbol-30px me-5 mb-8">
                      <span class="symbol-label">
                        <i class="ki-duotone ki-wallet fs-2x text-success">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                        </i>
                      </span>
                    </div>
                    <div class="m-0 d-flex justify-content-between align-items-center">
                      <div>
                        <span class="text-success fw-bolder d-block fs-2x lh-1 ls-n1 mb-1">
                          Rp{{ currency(data?.pendapatan, { notation: "compact", maximumFractionDigits: 2 }) }}
                        </span>
                        <span class="text-gray-500 fw-semibold fs-6">Pendapatan Tahun Ini</span>
                      </div>
                      <i class="fa fa-chevron-right text-success fs-2"></i>
                    </div>
                  </router-link>
                </div>
                <div class="col-md-6">
                  <router-link to="/dashboard/transaksi/pembelian"
                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-primary border-left-5 border-start d-block">
                    <div class="symbol symbol-30px me-5 mb-8">
                      <span class="symbol-label">
                        <i class="ki-duotone ki-basket fs-2x text-primary">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                        </i>
                      </span>
                    </div>
                    <div class="m-0 d-flex justify-content-between align-items-center">
                      <div>
                        <span class="text-primary fw-bolder d-block fs-2x lh-1 ls-n1 mb-1">
                          Rp{{ currency(data?.belanja, { notation: "compact", maximumFractionDigits: 2 }) }}
                        </span>
                        <span class="text-gray-500 fw-semibold fs-6">Belanja Tahun Ini</span>
                      </div>
                      <i class="fa fa-chevron-right text-primary fs-2"></i>
                    </div>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card">
          <div
            class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-light-dark">
            <h3 class="card-title align-items-start flex-column pt-10">
              <span class="fw-bold fs-2x">Pengesahan</span>
            </h3>
          </div>
          <div class="card-body mt-n20">
            <div class="mt-n20 position-relative">
              <div class="row g-3 g-lg-6">
                <div class="col-md-6">
                  <div
                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-success border-left-5 border-start d-block">
                    <div class="symbol symbol-30px me-5 mb-8">
                      <span class="symbol-label">
                        <i class="ki-duotone ki-wallet fs-2x text-success">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                        </i>
                      </span>
                    </div>
                    <div class="m-0 d-flex justify-content-between align-items-center">
                      <div>
                        <span class="text-success fw-bolder d-block fs-2x lh-1 ls-n1 mb-1">
                          Rp{{ currency(data?.pengesahan.pendapatan, { notation: "compact", maximumFractionDigits: 2 }) }}
                        </span>
                        <span class="text-gray-500 fw-semibold fs-6">Pendapatan BLU</span>
                      </div>
                      <!-- <i class="fa fa-chevron-right text-success fs-2"></i> -->
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div
                    class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-primary border-left-5 border-start d-block">
                    <div class="symbol symbol-30px me-5 mb-8">
                      <span class="symbol-label">
                        <i class="ki-duotone ki-basket fs-2x text-primary">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                        </i>
                      </span>
                    </div>
                    <div class="m-0 d-flex justify-content-between align-items-center">
                      <div>
                        <span class="text-primary fw-bolder d-block fs-2x lh-1 ls-n1 mb-1">
                          Rp{{ currency(data?.pengesahan.belanja, { notation: "compact", maximumFractionDigits: 2 }) }}
                        </span>
                        <span class="text-gray-500 fw-semibold fs-6">Belanja BLU</span>
                      </div>
                      <!-- <i class="fa fa-chevron-right text-primary fs-2"></i> -->
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card mb-10">
      <div
        class="card-header rounded bgi-no-repeat bgi-size-cover bgi-position-y-top bgi-position-x-center align-items-start h-200px bg-light-dark">
        <h3 class="card-title align-items-start flex-column pt-10">
          <span class="fw-bold fs-2x">Saldo Rekening</span>
        </h3>
      </div>
      <div class="card-body mt-n20">
        <div class="mt-n20 position-relative">
          <div
            class="bg-gray-100 bg-opacity-70 rounded-2 px-6 py-5 border-light-success border-left-5 border-start d-block">
            <div class="table-responsive">
              <table class="table table-striped gy-7 gs-7">
                <thead>
                  <tr class="fw-semibold fs-6 text-gray-800 border-bottom border-gray-200">
                    <th>Bank</th>
                    <th>No. Rekening</th>
                    <th>Atas Nama</th>
                    <th>Saldo</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="rekening in data?.rekenings" :key="rekening.id">
                    <td>{{ rekening.bank }}</td>
                    <td>{{ rekening.no_rek }}</td>
                    <td>{{ rekening.atas_nama }}</td>
                    <td>Rp{{ currency(data?.pengesahan.belanja) }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>
