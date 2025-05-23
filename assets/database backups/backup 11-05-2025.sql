PGDMP  )    !                }           postgres    17.4    17.4 #    G           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            H           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            I           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            J           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            K           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4938            �            1259    16541 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    submissions text[],
    image text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    16490    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    16496    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    217            L           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    218            �            1259    16497    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variations text[],
    variation_of text,
    challenge_type text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    16506    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    219            M           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    220            �            1259    16507    users    TABLE     �   CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16512    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    221            N           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    222            �           2604    16513    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    218    217            �           2604    16514    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    220    219            �           2604    16515    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    222    221            D          0    16541 
   challenges 
   TABLE DATA           j   COPY public.challenges (name, description, date_start, date_end, winners, submissions, image) FROM stdin;
    public               postgres    false    223   �(       >          0    16490    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    217   �)       @          0    16497    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variations, variation_of, challenge_type) FROM stdin;
    public               postgres    false    219   �)       B          0    16507    users 
   TABLE DATA           \   COPY public.users (id, username, email, password, likedsnippets, savedsnippets) FROM stdin;
    public               postgres    false    221   z,       O           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    218            P           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 35, true);
          public               postgres    false    220            Q           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 3, true);
          public               postgres    false    222            �           2606    16548    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    223            �           2606    16517    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    217            �           2606    16519    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    219            �           2606    16521    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    221            �           2606    16523    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    221            �           2606    16525    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    221            �           2606    16526    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    4768    217    219            �           2606    16531    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    221    217    4774            �           2606    16536    snips snips_creator_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    221    4774    219            �           2606    16549    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_type) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    4776    219    223            D     x����j�0�����3��e�K�@�J�KL���봾}�Z�B�B��9��`!��+x����k�i�[䄠P��TH�-pHn�ǎh�*�SΥ\+BC �H*WMX@�OalL��NI��g'�?����g���螅KtZ����pc�L�ʚ�yɫ
�@W�"����}���SV���~R�MZ�Z��H5��W�t�s�6gUj�a�"�����`�j�~r��y�M�bS��/�JiW�[����Qd/j�5o��x�N�9���hK[      >      x������ � �      @   �  x��T[o�0}v~���"���T���VMڦJ��T�r��W�)��4���ejTa.6�|�/�F6P
(���� �#�|L8x�U�Xw��[(���������xGO�C�����a#j�����a�w+Y?��+\����9b��DG�:��!�@Җ�F0ֺ�M��Uk�.�n9N�]�k���-�T몐�GY����մ���L�܈b"B�=�LF�F�:!��.�0��7"Cw�� �Ł�Л"Qk������'���b�0"�a��J7b��0�%LU&�88pW����Mv��BO״-z��o�\ٝ��ţz�ܳ^��%�����q��z4�J�||������̢[�FOXSͳ�Hč(�8�5�0`����_�#�"1�{Э�GmD^�'F����'q!�K#Bc��k;Z9��N���`�@�#�"�;���N;r����l��a����������-�E��ݸ�k{P��l�\���\�b�K	=�ӵGn�x��~]s"޺���$������N���9����	q��4FCaˬ���BH�س�6ى���Ⱥه���Ժ�4����~�-c�t9����!���+wY�`��Do9�p���?G>8ems1���9����,Ϻ6�Ύ�˲9��>�Q6�D�������w�c{����'ܞ��b�|_�o      B     x�m�Ao�0 ��s�M[�w�E:�D�Ka���"���..q�/���P̣��%����e���iZP.`"0 �@ǃ3~��$��[á���DX�B+��b��� ZE�jhZ_���ϩ��fU���z/� G^1!n����}T7�J�rE���>M�Y^Þ�֝g��o9zw"�eޞ�5�W��O��#s��ڑ'܍������u���.����6���\؍"=�[/o.������������._��i�7� v�     