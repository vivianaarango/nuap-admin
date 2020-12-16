<div class="form-group row align-items-center" :class="{'has-danger': errors.has('issues'), 'has-success': this.fields.issues && this.fields.issues.valid }">
    <label for="issues" class="col-form-label text-md-right" :class="'col-md-3'">Asunto</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <input type="text" required class="form-control" :class="{'form-control-danger': errors.has('issues'), 'form-control-success': this.fields.issues && this.fields.issues.valid}" id="issues" name="issues" placeholder="Asunto">
        <div v-if="errors.has('issues')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('issues') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('message'), 'has-success': this.fields.message && this.fields.message.valid }">
    <label for="message" class="col-form-label text-md-right" :class="'col-md-3'">Mensaje</label>
    <div :class="'col-md-4 col-md-9 col-xl-7'">
        <textarea rows="6" required class="form-control" :class="{'form-control-danger': errors.has('message'), 'form-control-success': this.fields.message && this.fields.message.valid}" id="message" name="message" placeholder="Mensaje"></textarea>
        <div v-if="errors.has('message')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('message') }}</div>
    </div>
</div>
