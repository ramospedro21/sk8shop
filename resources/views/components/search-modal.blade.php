
<!-- Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Procurando algo?</h5>
            </div>
            <form action="{{ url('/pesquisa') }}" method="GET">
                <div class="modal-body py-4 bg-light">
                    <input type="text" placeholder="Digite aqui o que procura..." class="form-control py-3 px-4 my-0" name="search">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search mr-2"></i> Buscar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
