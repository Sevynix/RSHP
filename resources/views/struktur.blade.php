@extends('layouts.app')

@section('content')
    <section id="struktur">
        <h2>Struktur Organisasi</h2>
        <div class="struktur">
            <div class="level">
                <div class="isikonten">
                    <div class="jabatan">DIREKTUR</div>
                    <img src="{{ asset('images/direktur.png') }}" alt="Direktur">
                    <div class="name">Dr. Ira Sari Yudaniyanti, M.P., drh.</div>
                </div>
            </div>
            <div class="level">
                <div class="isikonten">
                    <div class="jabatan">WAKIL DIREKTUR 1 <br> Pelayanan Medis, Pendidikan dan Penelitian</div>
                    <img src="{{ asset('images/wakil1.png') }}" alt="Wakil Direktur 1">
                    <div class="name">Dr. Nusdianto Triakoso, M.P., drh.</div>
                </div>
                <div class="isikonten">
                    <div class="jabatan">WAKIL DIREKTUR 2 <br> SDM, Sarana Prasarana, dan Keuangan</div>
                    <img src="{{ asset('images/wakil2.png') }}" alt="Wakil Direktur 2">
                    <div class="name">Dr. Miyayu Soneta S., M.Vet., drh.</div>
                </div>
            </div>
        </div>
    </section>
@endsection
